<?php

namespace App\Http\Controllers\exports;

use App\Http\Controllers\Controller;
use App\Models\product;
use App\Models\tables;
use Carbon\Carbon;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class AdisyonExportController extends Controller
{
  public function exportExcel($day)
  {
    // Günlük adisyonları al
    $adisyons = \App\Models\shopcart::whereDate('created_at', $day)->where('isPaid', 1)->where('isOnCredit',0)->get();
    $soldProducts = \App\Models\product_shopcart::whereDate('created_at', $day)->where('isPaid', 1)->get(); // Günlük satılan ürünler

    // Spreadsheet oluştur
    $spreadsheet = new Spreadsheet();

    // İlk tablo: Günlük adisyonlar
    $sheet1 = $spreadsheet->getActiveSheet();
    $sheet1->setTitle('Adisyon ' . \Carbon\Carbon::parse($day)->format('d-m-Y'));

    // Adisyon başlıkları
    $sheet1->setCellValue('A1', 'Adisyon Numarası');
    $sheet1->setCellValue('B1', 'Masa İsmi');
    $sheet1->setCellValue('C1', 'Tutar');
    $sheet1->setCellValue('D1', 'Tarih');

    // Stil ayarları (Başlıklar)
    $headerStyle = [
      'font' => ['bold' => true],
      'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER],
      'fill' => [
        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
        'startColor' => ['argb' => 'D3D3D3'], // Gri arka plan
      ],
      'borders' => [
        'allBorders' => [
          'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
          'color' => ['argb' => '000000'], // Siyah kenar
        ],
      ],
    ];
    $sheet1->getStyle('A1:D1')->applyFromArray($headerStyle);

    // Adisyon verileri
    $row = 2;
    foreach ($adisyons as $adisyon) {
      $table = tables::find($adisyon->table_id)->first();
      $sheet1->setCellValue('A' . $row, $adisyon->id);
      $sheet1->setCellValue('B' . $row, $table->title);
      $sheet1->setCellValue('C' . $row, $adisyon->all_total);
      $sheet1->setCellValue('D' . $row, $adisyon->created_at->format('d-m-Y H:i'));

      $sheet1->getStyle('A' . $row . ':D' . $row)->applyFromArray([
        'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER],
        'borders' => [
          'allBorders' => [
            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
            'color' => ['argb' => '000000'],
          ],
        ],
        'fill' => [
          'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
          'startColor' => ['argb' => 'D3D3D3'],
        ],
      ]);
      $row++;
    }

    // Adisyon toplamı
    $totalRow = $row;
    $sheet1->mergeCells('A' . $totalRow . ':B' . $totalRow);
    $sheet1->setCellValue('A' . $totalRow, 'Toplam:');
    $sheet1->setCellValue('C' . $totalRow, $adisyons->sum('all_total'));

    $sheet1->getStyle('A' . $totalRow . ':D' . $totalRow)->applyFromArray([
      'font' => ['bold' => true, 'color' => ['argb' => 'FFFFFF']],
      'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER],
      'fill' => [
        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
        'startColor' => ['argb' => '4CAF50'], // Yeşil arka plan
      ],
      'borders' => [
        'allBorders' => [
          'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
          'color' => ['argb' => '000000'],
        ],
      ],
    ]);

    // İkinci tablo: Satılan ürünler
    $sheet2 = $spreadsheet->createSheet();
    $sheet2->setTitle('Satılan Ürünler');

    // Ürün başlıkları
    $sheet2->setCellValue('A1', 'Ürün Adı');
    $sheet2->setCellValue('B1', 'Satılan Ürün Adedi');
    $sheet2->setCellValue('C1', 'Birim Maliyeti');
    $sheet2->setCellValue('D1', 'Birim Fiyat');
    $sheet2->setCellValue('E1', 'Toplam Maliyeti');
    $sheet2->setCellValue('F1', 'Toplam Fiyat');
    $sheet2->setCellValue('G1', 'Toplam Kar');

    $sheet2->getStyle('A1:G1')->applyFromArray($headerStyle);

    // Satılan ürünleri gruplayarak toplam miktarları hesapla
    $groupedProducts = $soldProducts->groupBy('product_id');

    // Ürün verileri
    $row = 2;
    foreach ($groupedProducts as $productId => $products) {
      $productDetails = Product::find($productId); // Ürün bilgileri
      $totalQuantity = $products->sum('quantity'); // Aynı üründen toplam satış adedi
      if ($productDetails) {
        $sheet2->setCellValue('A' . $row, $productDetails->title);
        $sheet2->setCellValue('B' . $row, $totalQuantity);
        $sheet2->setCellValue('C' . $row, $productDetails->cost);
        $sheet2->setCellValue('D' . $row, $productDetails->price);
        $sheet2->setCellValue('E' . $row, $productDetails->cost * $totalQuantity);
        $sheet2->setCellValue('F' . $row, $productDetails->price * $totalQuantity);
        $sheet2->setCellValue('G' . $row, $productDetails->price * $totalQuantity - $productDetails->cost * $totalQuantity);
      } else {
        $sheet2->setCellValue('A' . $row, 'Silinmiş Ürün');
        $sheet2->setCellValue('B' . $row, $totalQuantity);
        $sheet2->setCellValue('C' . $row, 'Silinmiş Maliyet');
        $sheet2->setCellValue('D' . $row, 'Silinmiş Fiyat');
        $sheet2->setCellValue('E' . $row, 'Silinmiş Toplam Maliyet');
        $sheet2->setCellValue('F' . $row, 'Silinmiş Toplam Fiyat');
        $sheet2->setCellValue('G' . $row, 'Silinmiş Kar');
      }

      $sheet2->getStyle('A' . $row . ':G' . $row)->applyFromArray([
        'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER],
        'borders' => [
          'allBorders' => [
            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
            'color' => ['argb' => '000000'],
          ],
        ],
        'fill' => [
          'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
          'startColor' => ['argb' => 'D3D3D3'],
        ],
      ]);
      $row++;
    }
    // Adisyon tablosu için sütun genişliğini ayarla
    foreach (range('A', 'D') as $column) {
      $sheet1->getColumnDimension($column)->setAutoSize(true);
    }

// Satılan ürünler tablosu için sütun genişliğini ayarla
    foreach (range('A', 'G') as $column) {
      $sheet2->getColumnDimension($column)->setAutoSize(true);
    }


    // Dosyayı oluştur ve indir
    $writer = new Xlsx($spreadsheet);
    $fileName = "Adisyonlar_$day.xlsx";

    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header("Content-Disposition: attachment; filename=\"$fileName\"");
    $writer->save('php://output');
    exit;
  }
//*********************** AYLIK EXCEL *******************************
  public function exportExcelMonth($month)
  {
    // $month örneği: '2025-02' şeklinde gelir
    $year = explode('-', $month)[0];
    $monthNumber = explode('-', $month)[1];

    // Aylık adisyonları al
    $adisyons = \App\Models\shopcart::whereYear('created_at', $year)
      ->whereMonth('created_at', $monthNumber)
      ->where('isPaid', 1)
      ->where('isOnCredit',0)
      ->get();

    $soldProducts = \App\Models\product_shopcart::whereYear('created_at', $year)
      ->whereMonth('created_at', $monthNumber)
      ->where('isPaid', 1)
      ->get(); // Aylık satılan ürünler

    // Spreadsheet oluştur
    $spreadsheet = new Spreadsheet();

    // İlk tablo: Günlük adisyonlar
    $sheet1 = $spreadsheet->getActiveSheet();
    $sheet1->setTitle('Adisyon ' . \Carbon\Carbon::parse($month)->format('m-Y'));

    // Adisyon başlıkları
    $sheet1->setCellValue('A1', 'Adisyon Numarası');
    $sheet1->setCellValue('B1', 'Masa İsmi');
    $sheet1->setCellValue('C1', 'Tutar');
    $sheet1->setCellValue('D1', 'Tarih');

    // Stil ayarları (Başlıklar)
    $headerStyle = [
      'font' => ['bold' => true],
      'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER],
      'fill' => [
        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
        'startColor' => ['argb' => 'D3D3D3'], // Gri arka plan
      ],
      'borders' => [
        'allBorders' => [
          'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
          'color' => ['argb' => '000000'], // Siyah kenar
        ],
      ],
    ];
    $sheet1->getStyle('A1:D1')->applyFromArray($headerStyle);

    // Adisyon verileri
    $row = 2;
    foreach ($adisyons as $adisyon) {
      $table = tables::find($adisyon->table_id)->first();
      $sheet1->setCellValue('A' . $row, $adisyon->id);
      $sheet1->setCellValue('B' . $row, $table->title);
      $sheet1->setCellValue('C' . $row, $adisyon->all_total);
      $sheet1->setCellValue('D' . $row, $adisyon->created_at->format('d-m-Y H:i'));

      $sheet1->getStyle('A' . $row . ':D' . $row)->applyFromArray([
        'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER],
        'borders' => [
          'allBorders' => [
            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
            'color' => ['argb' => '000000'],
          ],
        ],
        'fill' => [
          'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
          'startColor' => ['argb' => 'D3D3D3'],
        ],
      ]);
      $row++;
    }

    // Adisyon toplamı
    $totalRow = $row;
    $sheet1->mergeCells('A' . $totalRow . ':B' . $totalRow);
    $sheet1->setCellValue('A' . $totalRow, 'Toplam:');
    $sheet1->setCellValue('C' . $totalRow, $adisyons->sum('all_total'));

    $sheet1->getStyle('A' . $totalRow . ':D' . $totalRow)->applyFromArray([
      'font' => ['bold' => true, 'color' => ['argb' => 'FFFFFF']],
      'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER],
      'fill' => [
        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
        'startColor' => ['argb' => '4CAF50'], // Yeşil arka plan
      ],
      'borders' => [
        'allBorders' => [
          'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
          'color' => ['argb' => '000000'],
        ],
      ],
    ]);

    // İkinci tablo: Satılan ürünler
    $sheet2 = $spreadsheet->createSheet();
    $sheet2->setTitle('Satılan Ürünler');

    // Ürün başlıkları
    $sheet2->setCellValue('A1', 'Ürün Adı');
    $sheet2->setCellValue('B1', 'Satılan Ürün Adedi');
    $sheet2->setCellValue('C1', 'Birim Maliyeti');
    $sheet2->setCellValue('D1', 'Birim Fiyat');
    $sheet2->setCellValue('E1', 'Toplam Maliyeti');
    $sheet2->setCellValue('F1', 'Toplam Fiyat');
    $sheet2->setCellValue('G1', 'Toplam Kar');

    $sheet2->getStyle('A1:G1')->applyFromArray($headerStyle);

    // Satılan ürünleri gruplayarak toplam miktarları hesapla
    $groupedProducts = $soldProducts->groupBy('product_id');

    // Ürün verileri
    $row = 2;
    foreach ($groupedProducts as $productId => $products) {
      $productDetails = Product::find($productId); // Ürün bilgileri
      $totalQuantity = $products->sum('quantity'); // Aynı üründen toplam satış adedi
      if ($productDetails) {
        $sheet2->setCellValue('A' . $row, $productDetails->title);
        $sheet2->setCellValue('B' . $row, $totalQuantity);
        $sheet2->setCellValue('C' . $row, $productDetails->cost);
        $sheet2->setCellValue('D' . $row, $productDetails->price);
        $sheet2->setCellValue('E' . $row, $productDetails->cost * $totalQuantity);
        $sheet2->setCellValue('F' . $row, $productDetails->price * $totalQuantity);
        $sheet2->setCellValue('G' . $row, $productDetails->price * $totalQuantity - $productDetails->cost * $totalQuantity);
      } else {
        $sheet2->setCellValue('A' . $row, 'Silinmiş Ürün');
        $sheet2->setCellValue('B' . $row, $totalQuantity);
        $sheet2->setCellValue('C' . $row, 'Silinmiş Maliyet');
        $sheet2->setCellValue('D' . $row, 'Silinmiş Fiyat');
        $sheet2->setCellValue('E' . $row, 'Silinmiş Toplam Maliyet');
        $sheet2->setCellValue('F' . $row, 'Silinmiş Toplam Fiyat');
        $sheet2->setCellValue('G' . $row, 'Silinmiş Kar');
      }

      $sheet2->getStyle('A' . $row . ':G' . $row)->applyFromArray([
        'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER],
        'borders' => [
          'allBorders' => [
            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
            'color' => ['argb' => '000000'],
          ],
        ],
        'fill' => [
          'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
          'startColor' => ['argb' => 'D3D3D3'],
        ],
      ]);
      $row++;
    }

    // Aylık giderleri al
    $expenses = \App\Models\Gider::whereYear('created_at', $year)
      ->whereMonth('created_at', $monthNumber)
      ->get();

    // Üçüncü tablo: Giderler
    $sheet3 = $spreadsheet->createSheet();
    $sheet3->setTitle('Giderler');

    // Gider başlıkları
    $sheet3->setCellValue('A1', 'Gider Kategorisi');
    $sheet3->setCellValue('B1', 'Gider Adı');
    $sheet3->setCellValue('C1', 'Tutar');
    $sheet3->setCellValue('D1', 'Tarih');

    $sheet3->getStyle('A1:D1')->applyFromArray($headerStyle);

    // Gider verileri
    $row = 2;
    foreach ($expenses as $expense) {
      $category = \App\Models\gider_cat::find($expense->cat_id);
      $categoryName = $category ? $category->name : 'Kategori Bulunamadı';

      $sheet3->setCellValue('A' . $row, $categoryName);
      $sheet3->setCellValue('B' . $row, $expense->name);
      $sheet3->setCellValue('C' . $row, $expense->amount);
      $sheet3->setCellValue('D' . $row, $expense->created_at->format('d-m-Y'));

      $sheet3->getStyle('A' . $row . ':D' . $row)->applyFromArray([
        'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER],
        'borders' => [
          'allBorders' => [
            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
            'color' => ['argb' => '000000'],
          ],
        ],
        'fill' => [
          'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
          'startColor' => ['argb' => 'D3D3D3'],
        ],
      ]);
      $row++;
    }

    // Gider toplamı
    $totalRow = $row;
    $sheet3->mergeCells('A' . $totalRow . ':B' . $totalRow);
    $sheet3->setCellValue('A' . $totalRow, 'Toplam Gider:');
    $sheet3->setCellValue('C' . $totalRow, $expenses->sum('amount'));

    $sheet3->getStyle('A' . $totalRow . ':D' . $totalRow)->applyFromArray([
      'font' => ['bold' => true, 'color' => ['argb' => 'FFFFFF']],
      'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER],
      'fill' => [
        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
        'startColor' => ['argb' => 'FF0000'], // Kırmızı arka plan (Giderler)
      ],
      'borders' => [
        'allBorders' => [
          'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
          'color' => ['argb' => '000000'],
        ],
      ],
    ]);

    // Adisyon tablosu için sütun genişliğini ayarla
    foreach (range('A', 'D') as $column) {
      $sheet1->getColumnDimension($column)->setAutoSize(true);
    }

    // Satılan ürünler tablosu için sütun genişliğini ayarla
    foreach (range('A', 'G') as $column) {
      $sheet2->getColumnDimension($column)->setAutoSize(true);
    }

    // Giderler tablosu için sütun genişliği ayarla
    foreach (range('A', 'D') as $column) {
      $sheet3->getColumnDimension($column)->setAutoSize(true);
    }


    // Dosyayı oluştur ve indir
    $writer = new Xlsx($spreadsheet);
    $fileName = "Adisyonlar_$month.xlsx";

    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header("Content-Disposition: attachment; filename=\"$fileName\"");
    $writer->save('php://output');
    exit;
  }

}

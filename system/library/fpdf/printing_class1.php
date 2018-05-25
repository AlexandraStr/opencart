<?php
class Printing extends FPDF
{
    function Title($title,$logo, $owner,$customer, $company_site)
    {
        $this->Image($logo, 6, 6, 30, 20);
        $this->Cell(30);
        $this->SetFont('Tahoma-Bold', '', 10); // задаем шрифт, и размер шрифта
        $this->Cell(30,10,mb_convert_encoding("Постачальник :","cp1251","utf-8"));
        $this->Cell(30, 10, mb_convert_encoding($owner['name'], "cp1251", "utf-8"), 0, 10, 'L', 0); // выводим название компании
        $this->SetFont('Tahoma', '', 10);
        $this->Cell(70, 4, mb_convert_encoding($owner['adress'], "cp1251", "utf-8"), 0, 10, 'L', 0); // выводим адрес компании
        $this->SetFont('Tahoma', '', 8);
        $this->Cell(70, 4, mb_convert_encoding($owner['pdv'], "cp1251", "utf-8"), 0, 10, 'L', 0);
        $this->SetFont('Tahoma', '', 10);
        $this->Cell(40, 4, mb_convert_encoding($owner['count'], "cp1251", "utf-8"), 0, 10, 'L', 0); // выводим адрес компании
        $this->Cell(40, 4, mb_convert_encoding($owner['phone'], "cp1251", "utf-8"), 0, 10, 'L', 0); // выводим телeфон компании
        $this->Cell(40, 4, mb_convert_encoding($company_site, "cp1251", "utf-8"), 0, 10, 'L', 0); // выводим адрес сайта компании
        $this->ln(5);
        $this->Cell(30,10,mb_convert_encoding("Одержувач :","cp1251","utf-8"));
        $this->Cell(40, 4, mb_convert_encoding($customer['company'], "cp1251", "utf-8"), 0, 10, 'L', 0); // выводим ЕДРПО компании
        $this->Cell(40, 4, mb_convert_encoding($customer['fio'], "cp1251", "utf-8"), 0, 10, 'L', 0); // выводим ФИО
        $this->Cell(40, 4, mb_convert_encoding($customer['telephone'], "cp1251", "utf-8"), 0, 10, 'L', 0); // выводим телeфон компании
        $this->Cell(40, 4, mb_convert_encoding($customer['email'], "cp1251", "utf-8"), 0, 10, 'L', 0); // выводим email компании
        $this->ln(5);
        $this->Cell(30,10,mb_convert_encoding("Платник : той же ","cp1251","utf-8"));
        $this->ln(15);
        $this->SetFont('Tahoma-Bold', '', 15); // задаем шрифт, и размер шрифта
        $this->Cell(70);
        $this->Cell(50, 4, mb_convert_encoding($title, "cp1251", "utf-8"), 0, 0, 'L', 0); // выводим наименование счета
        $this->ln(12); // переходим на следующую строку
    }

    function OutputTable($header,$data)
    {
        $w = array(10, 30, 70, 10, 30, 20, 20); // Массив с шириной столбцов
        $this->SetFont('Tahoma', '', 10);
        for ($i = 0; $i < count($header); $i++) {
            $this->Cell($w[$i], 7, mb_convert_encoding($header[$i], "cp1251", "utf-8"), 1, 0, 'C', true);
        }
        $this->ln(10);
        //     $this->SetFont('ArialMT', '', 8);
        $this->SetFillColor(220,220,220);
        $total_sum = 0;
        $tax_sum = 0;
        $all_sum = 0;
        $nom = 1;
        foreach ($data as $row) {
            $fill=$nom % 2;
            $start_X = $this->GetX();
            $start_y = $this->GetY();
            $current_X = $start_X + $w[0] + $w[1];
            $this->SetXY($current_X, $start_y);
            $this->MultiCell($w[2], 7, mb_convert_encoding($row['name'], "cp1251", "utf-8"), 1, 'L', $fill);
            $height = $this->GetY() - $start_y;
            $this->SetXY($start_X, $start_y);
            $this->Cell($w[0], $height, $nom, 1, 0, 'L', $fill);
            $current_X = $this->GetX();
            $this->SetXY($current_X, $start_y);
            $this->Cell($w[1], $height, $row['model'], 1, 0, 'L', $fill);
            $current_X = $current_X + $w[1] + $w[2];
            $this->SetXY($current_X, $start_y);
            $this->Cell($w[3], $height, mb_convert_encoding("шт.", "cp1251", "utf-8"), 1, 0, 'L', $fill);
            $current_X = $current_X + $w[3];
            $this->SetXY($current_X, $start_y);
            $this->Cell($w[4], $height, (float)$row['price'], 1, 0, 'R', $fill);
            $current_X = $current_X + $w[4];
            $this->SetXY($current_X, $start_y);
            $this->Cell($w[5], $height, (int)$row['quantity'], 1, 0, 'R', $fill);
            $current_X = $current_X + $w[5];
            $this->SetXY($current_X, $start_y);
            $this->Cell($w[6], $height, (float)$row['total'], 1, 0, 'R', $fill);
            $this->ln();
            $nom = $nom + 1;
            $tax_sum = (float)$tax_sum + $row['tax']/100*(float)$row['price'];
            $total_sum = (float)$total_sum + (float)$row['price'];
        }
        $all_sum = $total_sum + $tax_sum;

        $this->ln(10);
        $this->Cell(100);
        $this->Cell(70, 5, mb_convert_encoding("Разом до сплати без ПДВ :  ".$total_sum." грн.", "cp1251", "utf-8"), 'B', 0, 'L', 0);
        $this->ln();
        $this->Cell(100);
        $this->Cell(70, 5, mb_convert_encoding("ПДВ :              ".$tax_sum." грн.", "cp1251", "utf-8"), 'B', 0, 'L', 0);
        $this->ln();
        $this->Cell(70, 5, mb_convert_encoding("Всього до сплати :   ".$all_sum." грн.", "cp1251", "utf-8"), 'B', 0, 'L', 0);
        $this->ln();
    }

}

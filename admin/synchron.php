<?php
$file_flag =  "/Applications/XAMPP/xamppfiles/htdocs/opencart/download/csv/upload.sng";
$filename = "/Applications/XAMPP/xamppfiles/htdocs/opencart/download/csv/import.csv";
$fields = array("model","quainty","price");
$delim = ";";
$header = 1;


$cont = trim( file_get_contents( $filename ) );
$encoded_cont = mb_convert_encoding( $cont, 'UTF-8', 'windows-1251' );

$row_delimiter = "";

// определим разделитель
if( !$row_delimiter ){
    $row_delimiter = "\r\n";
    if( false === strpos($encoded_cont, "\r\n") )
        $row_delimiter = "\n";
}

$lines = explode( $row_delimiter, $encoded_cont );
$lines = array_filter( $lines );
$lines = array_map( 'trim', $lines );

if($header) {
    array_shift($lines);
}

$data = [];
foreach( $lines as $line ){
    $data[] = str_getcsv( $line, $delim ); // linedata
}

$str_date = count($data,COUNT_RECURSIVE)/count($data)-1;

foreach ($data as $value) {
    for ($i = 0; $i < $str_date; $i++) {
        $setdate[$i] = $fields[$i]."='".$value[$i]."'";
    }
    $setstr=implode(",",$setdate);
    $wherestr=$fields[0]."= '".$value[0]."'";
}


return $data;

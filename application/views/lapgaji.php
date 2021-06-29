<?php
class PDF extends FPDF{

    function Header(){
        $this->setFont('Arial','',10);
        $this->setFillColor(255,255,255);
        $this->cell(100,6,"Laporan gaji",0,0,'L',1); 
        $this->cell(100,6,"Printed date : " . date('d/m/Y'),0,1,'R',1); 
        //$this->Image(base_url().'assets/dist/img/user7-128x128.jpg', 10, 25,'20','20','jpeg');
                
        $this->Ln(12);
        $this->setFont('Arial','',14);
        $this->setFillColor(255,255,255);
        $this->cell(85,6,'',0,0,'C',0); 
        $this->cell(100,6,'SLIP GAJI',0,1,'L',1); 
        $this->cell(85,6,'',0,0,'C',0); 
        $this->cell(100,6,date('M Y'),0,1,'L',1); 

            
        $this->Ln(5);
        $this->setFont('Arial','',10);
    }
 
    function Content($data){
        foreach($data->result() as $key) { 
            $this->setFont('Arial','',10);
            $this->setFillColor(255,255,255);
            $this->cell(10,8,'',0,0,'C',0);

            $this->cell(50,5,'Nama : ',0,0,'L',0);
            $this->cell(-35,8,'',0,0,'C',0);
            $this->cell(50,5,$key->nama,0,0,'L',0);
            $this->cell(1,5,'/',0,0,'C',0);
            $this->cell(10,5,$key->nik,0,0,'L',0);
            $this->cell(-76,8,'',0,0,'L',0);
            $this->cell(50,15,'Jabatan : ',0,0,'L',0);
            $this->cell(-35,8,'',0,0,'L',0);
            $this->cell(50,15,$key->jabatan,0,0,'L',0);
            $this->SetY(56);
            $this->Line(10,$this->GetY(),200,$this->GetY());
            $this->SetY(44);
            $this->Line(10,$this->GetY(),200,$this->GetY());
            
        }   
    }

    function Content2($data2){

        foreach($data2->result() as $key2) { 
            $this->SetFont('Arial','',10);
            $this->Ln(19);
            $this->Cell(1,0,$key2->keterangan,0,1,'L');
            $this->Ln(4);
            $this->Cell(1,0,$key2->nilai,0,1,'L');


            
        }   
    }

    function Footer()
    {
        //atur posisi 1.5 cm dari bawah
        $this->SetY(-15);
        //buat garis horizontal
        $this->Line(10,$this->GetY(),200,$this->GetY());
        //Arial italic 9
        $this->SetFont('Arial','I',9);
        $this->Cell(0,10,'company name ' . date('Y'),0,0,'L');
        //nomor halaman
        $this->Cell(0,10,'Halaman '.$this->PageNo().' dari {nb}',0,0,'R');
    }
}

$pdf = new PDF();
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->Content($data);
$pdf->Content2($data2);
$pdf->Output();
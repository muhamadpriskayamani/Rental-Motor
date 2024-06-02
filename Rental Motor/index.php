<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Rental Motor</title>
    <style>
        body,
        html {
            height: 100%;
            background-image: url(https://img.gta5-mods.com/q95/images/ducati-panigale-v4-engine-sound-mod/4af9c0-DUCATI-V4R-LAT-DIR.jpg);
            background-size: cover;
        }

        h1 {
            color: red;
        }

        .container {
            width: 60%;
            margin: 50px auto;
            background-color: rgba(0, 0, 0, 0.7);
            text-align: center;
            border-radius: 10px;
            padding: 20px;
            color: white;
        }

        button {
            width: 100px;
            padding: 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.1s;
            font-size: 15px;
        }

        .container img {
            width: 100px;
        }

        .output {
            background-color: rgba(255, 255, 255, 0.1);
            padding: 20px;
            border-radius: 10px;
            margin-top: 20px;
        }

        .output p {
            margin-bottom: 10px;
        }

        @media print {
            body * {
                visibility: hidden;
            }

            .output,
            .output * {
                visibility: visible;
                border: 1px solid black;
                orphans: 1;
                widows: 1;
            }
            .output button {
                display: none;
            }

            .output {
                width: 100%;
                margin: 0;
                background-color: white;
                color: black;
                padding: 30px;
                font-size: 20px;
            }

            .output h1 {
                color: red;
                font-size: 18px;
            }

            .output p {
                margin-bottom: 10px;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <div>
            <h1>Rental Motor</h1>
            <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                <div class="mb-3">
                    <label for="namaPelanggan" class="form-label">Nama Pelanggan:</label>
                    <input type="text" class="form-control" id="namaPelanggan" name="namaPelanggan">
                </div>
                <div class="mb-3">
                    <label for="lamaRental" class="form-label">Lama Waktu Rental (per hari):</label>
                    <input type="number" class="form-control" id="lamaRental" name="lamaRental">
                </div>
                <div class="mb-3">
                    <label for="jenisMotor" class="form-label">Jenis Motor:</label>
                    <select class="form-select" id="jenisMotor" name="jenisMotor">
                        <option value="Aerox">Aerox</option>
                        <option value="Ducati Panigale V4 R">Ducati Panigale V4 R</option>
                        <option value="Vespa Primavera">Vespa Primavera</option>
                        <option value="NMAX">NMAX</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary" style="background-color: red;">Submit</button>
            </form>
            <div class="output">
                <?php
                if ($_SERVER["REQUEST_METHOD"] == "POST") {
                    $namaPelanggan = $_POST['namaPelanggan'];
                    $lamaRental = $_POST['lamaRental'];
                    $jenisMotor = $_POST['jenisMotor'];
                    $hargaRentalPerHari = array(
                        "Aerox" => 80000,
                        "Ducati Panigale V4 R" => 150000,
                        "Vespa Primavera" => 90000,
                        "NMAX" => 60000
                    );

                    if (array_key_exists($jenisMotor, $hargaRentalPerHari)) {
                        $rental = new Buy($namaPelanggan, $hargaRentalPerHari[$jenisMotor], $lamaRental);
                        $rental->struk();
                    } else {
                        echo "<p>Jenis motor yang dipilih tidak valid.</p>";
                    }
                }

                class Rental
                {
                    protected $NamaPelanggan;
                    protected $Harga;
                    protected $Total;
                    protected $Pajak;
                    protected $Diskon;
                    protected $members;

                    public function __construct($NamaPelanggan, $Harga, $Total)
                    {
                        $this->NamaPelanggan = $NamaPelanggan;
                        $this->Harga = $Harga;
                        $this->Total = $Total;
                        $this->Pajak = 10000;
                        $this->Diskon = 5 / 100;
                        $this->members = array("Yamani", "Ucup", "Yadi", "Ryan");
                    }

                    public function getNamaPelanggan()
                    {
                        return $this->NamaPelanggan;
                    }

                    public function getHarga()
                    {
                        return $this->Harga;
                    }

                    public function getTotal()
                    {
                        return $this->Total;
                    }

                    public function getPajak()
                    {
                        return $this->Pajak;
                    }

                    public function getDiskon()
                    {
                        return $this->Diskon;
                    }

                    public function getMembers()
                    {
                        return $this->members;
                    }
                }

                class Buy extends Rental
                {
                    public function __construct($NamaPelanggan, $Harga, $Total)
                    {
                        parent::__construct($NamaPelanggan, $Harga, $Total);
                    }

                    public function HitungJumlah()
                    {
                        $total = ($this->Harga * $this->Total) + $this->Pajak;
                        if (in_array(strtolower($this->NamaPelanggan), array_map('strtolower', $this->getMembers()))) {
                            $total -= ($total * $this->Diskon);
                        }
                        return $total;
                    }

                    public function struk()
                    {
                        echo "<h1>Bukti Transaksi</h1>";
                        $total = $this->HitungJumlah();
                        echo "<p>" . $this->NamaPelanggan . " berstatus sebagai ";
                        if (in_array(strtolower($this->NamaPelanggan), array_map('strtolower', $this->getMembers()))) {
                            echo "member dan mendapat potongan harga 5%.</p>";
                        } else {
                            echo "Non-member.</p>";
                        }
                        echo "Jenis motor yang di rental adalah " . $_POST["jenisMotor"] . " selama " . $_POST["lamaRental"] . " hari";
                        echo "<p>Harga Rental Per Hari: Rp. " . number_format($this->Harga, 2, ',', '.') . "</p>";
                        echo "<p>Total Harga (termasuk pajak): Rp. " . number_format($total, 2, ',', '.') . "</p>";
                        echo '<button onclick="window.print()">Print</button>';
                    }
                }
                ?>
            </div>
        </div>
    </div>
</body>
</html>

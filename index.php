<?php
     //Array yang disiapkan untuk menyimpan data
    $todos = array();

    //Jika ada file todo.txt, baca file nya!
    if(file_exists("todo.txt")){
        $file = file_get_contents("todo.txt");
        $todos = unserialize($file);   
    }

    //Jika terdapat data yang dikirim dari form, maka tangkap datanya
    if(isset($_POST["todo"])){
        $data = $_POST["todo"];
        $todos[] = [
            "todo" => "$data",
            "status" => 0
        ];
        //Data baru yang datang akan dibuat dalam bentuk array assosiatif sebagai element variable todos.
        //Dimana key dari setiap array assosiatif tersebut adalah todo dan status.
        $serialized_todos = serialize($todos);
        file_put_contents("todo.txt", $serialized_todos);

        //Setelah data disubmit dan disimpan dalam di file todo.txt, maka data tersebut masih tersimpan
        //di laman index.php ini dalam $_POST["todo"]. Oleh karena itu kita gunakan fungsi di PHP yang
        //berguna untuk mendirect laman ke laman yang lain(atau bisa juga laman itu sendiri) menggunakan fungsi header()
        //agar saat laman di refresh data yang tersimpan akan diabaikan karena laman akan di direct ke laman yang lain.
        header("Location:index.php");
    }

    // Kode PHP dibawah akan jalan ketika checkbox ToDo di pencet (dimana ketika di pencet, telah di set sebuah kode JS yang
    // akan memberikan sebuah query string yang berguna untuk mereset status ToDo yang sesuai).
    if(isset($_GET["status"])){
        $todos[$_GET["key"]]["status"] = $_GET["status"];
        // Kode dibawah sama fungsi nya dengan kode di baris 18 (yaitu untuk menyimpan data yang masuk ke dalam file todo.txt)
        $serialized_todos = serialize($todos);
        file_put_contents("todo.txt", $serialized_todos);
        header("Location:index.php");
    }

    // Kode PHP dibawah digunakan untuk menghapus data array ke key dengan menggunakan fungsi khusus unset(), lalu menyimpannya ke dalam file todo.txt
    // dan mendirect paksa halaman ke index.php kembali
    if(isset($_GET["hapus"])){
        unset($todos[$_GET["key"]]);
        $serialized_todos = serialize($todos);
        file_put_contents("todo.txt", $serialized_todos);
        header("Location:index.php");
    }

    // NOTES
    // NOTES
    // NOTES
    // Apabila dilihat di beberapa perkondisian IF diatas, dapat dilihat terdapat beberapa fungsi yang sama yang mungkin dapat di jadi satukan dalam sebuah fungsi 
    // Namun dalam codingan ini tidak dijadisatukan karena terdapat beberapa notes yang tidak bisa dipisah.
?>

<h1>ToDo App</h1>

<!-- FORM kita mengisi data ToDo -->
<form action="" method="POST">
    <label>Apa kegiatanmu hari ini?</label>
    <input type="text" name="todo">
    <button type="submit">Simpan!</button>
</form>

<!-- List kita melihat hasil data ToDo kita -->
<ul>
    <?php foreach($todos as $key => $value): ?>
    <li>
        <!-- Kode JS onclick dibawah digunakan untuk memaksa program berpindah ke halaman yang ada di kode href dengan
        tambahan query string yang berguna untuk meng-set nilai status isi ToDo menjadi 1/0 sesuai key nya-->
        <input type="checkbox" name="todo" onclick="window.location.href='index.php?status=<?php echo ($value["status"]==1)?'0':'1'?>&key=<?php echo $key?>'" <?php if($value["status"]==1) echo "checked"?>>
        <label>
            <!-- Kode PHP dibawah digunakan untuk memberikan garis tengah pada ToDo yang sudah di centang / berstatus = 1 sesuai attribute <del> HTML -->
            <?php
                if($value["status"]==1){
                    echo "<del>" . $value["todo"] . "</del>";
                }
                else{
                    echo $value["todo"];
                }
            ?>
        </label>
        <!-- Sedikit kode HTML dan PHP dibawah digunakan untuk memaksa program berpindah ke halaman yang ada di href dengan tambahan query string yang berguna untuk 
        menghapus ToDo yang sesuai -->
        <!-- Sedikit kode JS dibawah digunakan untuk memberikan jendela pop up kepada user untuk memeastikan apakah user ingin menghapus ToDo atau tidak.
        Terdapat keyword return dimana fungsi confirm pada akhirnya mempunyai dua kembalian nilai yaitu true dan false. Jika true, maka href jalan, dan false
        hrefnya gajalan  -->
        <!-- Kalo keyword returnnya gada, maka biar di cancel pop upnya akan tetap ngehapus -->
        <a href="index.php?hapus=1&key=<?php echo $key ?>" onclick="return confirm('Apakah anda ingin menghapus data ini?')">Hapus</a>
    </li>
    <?php endforeach ?>
</ul>

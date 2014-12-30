<html>
<head>
	<title>Daftar on SickHouse!</title>

    <style type="text/css">

    .content {
        min-height: 480px;
    }


    .hdreg {
        width: 100%;
        background: #00AD87;
        min-height: 120px;
    }

    input {
    height: 30px;
    border-style: none;
    width: 234px;
    }

    input[type=submit],input[type=file]  {
        border: 0px;
        color: #1FCFAA;
        background-color: #ffffff;
    }

    input[type=submit]:hover {
        color: #ffffff;
        border: 0px;
        text-shadow: 0 1px rgba(0,0,0,0.3);
        background-color: #00AD87;
  
    }

    input[type=text]:hover, input[type=password]:hover,input[type=textarea]:hover {
        border: 1px solid #b9b9b9;
        border-top: 1px solid #a0a0a0;
    }

    table {
        margin: 2% auto;
        min-width: 500px;
    }

    h2 {
	padding: 6% 0% 0% 0%;
        text-align: center;
        color: #fff;
    }


    </style>

</head>
<body>
    <div class="hdreg">
        <h2>REGISTRATION</h2>
    </div>

    <div class="content">

        <!--/ FORM REGISTER USER /-->
        <form action="controller/controller_register.php" method="POST">
    <table>
        <tr>
            <td>Nama Lengkap</td><td>:</td><td><input type="text" name="nama" /></td>
        </tr>
        <tr>
            <td>Jenis Kelamin</td><td>:</td><td><input type="text" name="klmn" /></td>
        </tr>
        <tr>
            <td>Tanggal Lahir</td><td>:</td><td><input type="date" name="tgl" /></td>
        </tr>
        <tr>
            <td>Spesialisasi</td><td>:</td><td><input type="text" name="spesial" /></td>
        </tr>
        <tr>
            <td>Alamat</td><td>:</td><td><input type="textarea" name="alamat" /></td>
        </tr>
        <tr>
            <td>Kota</td><td>:</td><td><input type="text" name="kota" /></td>
        </tr>
        <tr>
            <td>Telepon</td><td>:</td><td><input type="text" name="telepon" /></td>
        </tr>
        <tr>
            <td>SIP</td><td>:</td><td><input type="text" name="sip" /></td>
        </tr>
        <tr>
            <td>Avatar</td><td>:</td><td><input type="file" name="atr" /></td>
        </tr>
        <tr>
            <td>Username</td><td>:</td><td><input type="text" name="username" /></td>
        </tr>
        <tr>
            <td>Password</td><td>:</td><td><input type="password" name="password" /></td>
        </tr>
        <tr>
            <td>Ulangi Password</td><td>:</td><td><input type="password" name="pwd" /></td>
        </tr>
        <tr>
            <td></td><td></td><td><input type="submit" name="submit" value="Register" /></td>
        </tr>
    </table>
    </form>

    </div>  
 
</body>
</html>

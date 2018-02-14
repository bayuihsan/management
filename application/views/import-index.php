<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>Import Excel</title>

        <link rel="stylesheet" href="<?= base_url()?>/assets/style.css" />
    </head>
    <body>

        <div id="container">
            <h1>Import Excel</h1>

            <div id="body">
                <div class="padd-bottom-10 alert-success"><?= $_alert?></div>
                <?= form_open_multipart(base_url('import/upload'))?>
                <div>
                    <label> Upload File</label>
                    <?= form_upload(array('id' => 'txtFileImport', 'name' => 'fileImport', 'accept' => '.csv, application/vnd.ms-excel, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'))?>
                </div>
                <div class="padd-top-20">
                    <?= form_submit(array('name' => 'btnSubmit', 'value' => 'Upload'))?>
                    <?= anchor(site_url(), 'Kembali')?>
                </div>
                <?= form_close()?>
            </div>

            <p class="footer">Page rendered in <strong>{elapsed_time}</strong> seconds</p>
        </div>

    </body>
</html>
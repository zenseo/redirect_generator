<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="">
    <meta name="author" content="">
    <title>Генератор редиректов</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css" type="text/css" rel="stylesheet" />

   
    <script data-main="/redirect_generator/frontend/lib/app" src="/redirect_generator/frontend/lib/require.min.js"></script>
</head>

<body>
    <div class="container">
        <div class="page-header">
            <h1>Генератор редиректов</h1>
            <p class="lead"></p>
        </div>
        <h3>Форма генерации</h3>
        <form enctype="multipart/form-data"  action="/redirect_generator/index.php" method="post">
            <div class="form-group">
                <label for="file">Файл для загрузки (.csv):</label>
                <input type="file" name="upload[]" id="file">
            </div>
            <div class="form-group">
                <label for=" ">Действие:</label>
                <div class="radio">
                    <label>
                        <input type="radio" name="action" id="redirect" value="redirect" checked>Редиректы
                    </label>
                </div>
                <div class="radio">
                    <label>
                        <input type="radio" name="action" id="canonical" value="canonical">Canonical
                    </label>
                </div>
            </div>
            <div class="form-group">
                <label for=" ">Форматирование:</label>
                <div class="radio">
                    <label>
                        <input data-type="redirect" type="radio" name="format" id="htaccess" value="htaccess" checked>.htaccess
                    </label>
                </div>
                <div class="radio">
                    <label>
                        <input data-type="redirect,canonical" type="radio" name="format" id="php" value="php"> PHP
                    </label>
                </div>
                <div class="radio">
                    <label>
                        <input data-type="redirect,canonical" type="radio" name="format" id="smarty" value="smarty"> Smarty
                    </label>
                </div>
            </div>
            <button type="submit" class="btn btn-default">Выполнить</button>
        </form>
        <hr />
        <div id="result">
        <h3>Результат</h3>
        <textarea name="result" class="form-control" rows="15"><?php echo $res; ?></textarea>
       </div>
<br /><br /><br /><br /><br />

    </div>
    <!-- /container -->
</body>

</html>

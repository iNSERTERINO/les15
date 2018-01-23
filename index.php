<?php
require_once 'config.php';
$url = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
try{
    $connectStr = DB_DRIVER.':host='.DB_HOST.';dbname='.DB_NAME;
    $db = new PDO($connectStr,DB_USER,DB_PASS);
    $db->exec("set names utf8");
}catch (PDOException $e) {
    print "Error!: " . $e->getMessage() . "<br/>";
    die();
} ?>
<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Document</title>
</head>
<body>
<?php
if (isset($_GET['tableDelete'])){
    $tableDelete = $db->prepare('DROP TABLE IF EXISTS '.$_GET['tableDelete']);
    $tableDelete->execute();
    header('Location: index.php');
}
if (isset($_GET['table'])){ ?>
    <a href="index.php">Назад</a>
    <?php
    $sql = $db->prepare("SHOW COLUMNS FROM ".$_GET['table']);
    $sql->execute();
    $sqlContent = $db->prepare("SELECT * FROM ".$_GET['table']);
    $sqlContent->execute();
    $res = $sql->fetchAll();
    $resContent = $sqlContent->fetchAll();
    if (isset($_GET['delete'])){
        echo "h1";
        $delete = $db->prepare("DELETE FROM ".$_GET['table']." WHERE  id LIKE ".$_GET['delete']);
        $delete->execute();
        header('Location: index.php?table='.$_GET['table']);
    }
    ?>

    <table>
        <thead>
        <tr>
            <td>Управление</td>
            <?php
            foreach ($res as $key){ ?>
                <td><?=$key['Field'] ?></td>
            <?php }
            ?>
        </tr>
        </thead>
        <tbody>
        <?php
        foreach ($resContent as $key){ ?>
        <tr>
            <td><a href="<?=$url ?>&edit=<?=$key['id'] ?>">изменить</a><a href="<?=$url ?>&delete=<?=$key['id'] ?>">удалить</a></td>
            <?php foreach ($res as $key2){ ?>


                <td><?=$key[$key2['Field']] ?></td>

            <?php }
            echo "</tr>";
            }
            ?>
        </tbody>
    </table>

<?php }
elseif (isset($_POST['tableFields'])){ ?>
    <a href="index.php">Назад</a> <br>
    Название создаваемой таблицы: <?=$_POST['tableName'] ?> <br>
    <form action="createtable.php" method="post">
        <table>
            <thead>
            <tr>
                <td>Имя</td>
                <td>Тип</td>
                <td>Длина/Значения</td>
                <td>A_I</td>
            </tr>
            </thead>
            <tbody>
            <?php
            for ($i = 0; $i < $_POST['tableFields'];$i++){ ?>
                <tr>
                    <td><input type="text" name="fieldName<?=$i ?>"></td>
                    <td><select name="typeName<?=$i ?>">
                            <option selected value="INT">INT</option>
                            <option value="VARCHAR">VARCHAR</option>
                            <option value="TEXT">TEXT</option>
                        </select></td>
                    <td><input type="text" name="longName<?=$i ?>" value="NOT_NULL"></td>
                    <td><input type="checkbox" name="aiName<?=$i ?>"></td>
                </tr>
                <input type="hidden" name="rowCount" value="<?=$i+1 ?>">
            <?php } ?>
            <input  type="hidden" name="tableName" value="<?=$_POST['tableName'] ?>">

            </tbody>
        </table>
        <br>
        <button type="submit">СОЗДАТЬ ТАБЛИЦУ</button>
    </form>
    <?php
}
else{ ?>
    <form action="" method="post">
        Название таблицы:
        <input type="text" name="tableName">
        Количество столбцов:
        <input type="number" name="tableFields" min="1" value="2">
        <button type="submit">Создать таблицу</button>
    </form>
    <table>
        <thead>
        <tr>
            <td>Таблица</td>
            <td>Управление</td>
        </tr>
        </thead>
        <tbody>
        <?php
        $sql = $db->prepare("SHOW TABLES FROM ".DB_NAME);
        $sql->execute();
        while ($res = $sql->fetch(PDO::FETCH_BOTH)){?>
            <tr>
                <td><a href="?table=<?=$res['Tables_in_'.DB_NAME]; ?>"><?=$res['Tables_in_'.DB_NAME]; ?></a></td>
                <td><a href="?tableEdit=<?=$res['Tables_in_'.DB_NAME]; ?>">Изменить</a><a href="?tableDelete=<?=$res['Tables_in_'.DB_NAME]; ?>">Удалить</a></td>
            </tr>
        <?php } ?>
        </tbody>
    </table>
<?php } ?>

</body>
</html>
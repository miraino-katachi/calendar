<?php
// 今月を0とする。
$month = 0;

// GETパラメータがあって、かつ、数値形式で、かつ、整数のとき
// is_int()関数は、「数値文字列」の場合はfalseを返却します。
// formやgetパラメータで受け取った「数値」は「文字列」になっています。
if (isset($_GET['month']) && is_numeric($_GET['month']) && is_int((int) $_GET['month'])) {
    $month = (int) $_GET['month'];
}

// 今月の「ついたち」のDateTimeクラスのインスタンスを生成する。
$dateTime = new DateTime();
$dateTime = new DateTime($dateTime->format("Y/m") . "/1");

if ($month > 0) {
    // $monthが0より大きいときは、現在月の「ついたち」に、その月数を追加する
    $dateTime->add(new DateInterval("P" . $month . "M"));
} else {
    // $monthが0より小さいときは、現在月の「ついたち」から、その月数を引く
    $dateTime->sub(new DateInterval("P" . (0 - $month) . "M"));
}

// 月初の日の曜日＝当月の「ついたち」までに、その週に何日あるか
$beginDayOfWeek = $dateTime->format('w');

// 月末の日＝当月に何日あるかの日数
$monthDays = $dateTime->format('t');

// 当月に何週あるか（日数切り上げ）
$weeks = ceil(($monthDays + $beginDayOfWeek) / 7);

// カレンダに記述する日付のカウンタ
$date = 1;
?>
<!DOCTYPE html>
<html lang="jp">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>カレンダー</title>
    <link rel="stylesheet" href="./css/normalize.css">
    <link rel="stylesheet" href="./css/main.css">
</head>

<body>
    <div class="container">
        <h1>カレンダー</h1>

        <table>
            <caption><?= $dateTime->format('Y年n月') ?></caption>
            <tr>
                <th>日</th>
                <th>月</th>
                <th>火</th>
                <th>水</th>
                <th>木</th>
                <th>金</th>
                <th>土</th>
            </tr>
            <!-- 当月にある週数分繰り返し -->
            <?php for ($week = 0; $week < $weeks; $week++) : ?>
                <tr>
                    <!-- 一週間の日数分（7日分）繰り返し -->
                    <?php for ($day = 0; $day < 7; $day++) : ?>
                        <td>
                            <?php
                            if ($week == 0 && $day >= $beginDayOfWeek) {
                                // 月の1週目で、かつ、月初の日（曜日）以上のときは、
                                // 日付のカウンタに1を足して表示する
                                echo $date++;
                            } elseif ($week > 0 && $date <= $monthDays) {
                                // 月の2週目以降で、かつ、月末の日までのときは、
                                // 日付のカウンタに1を足して表示する
                                echo $date++;
                            }
                            // その他の日は何も表示しない
                            ?>
                        </td>
                    <?php endfor ?>
                </tr>
            <?php endfor ?>
        </table>

        <div class="navi">
            <ul>
                <li><a href="./?month=<?= $month - 1 ?>">&lt;&lt;前の月</a></li>
                <li><a href="./">今月</a></li>
                <li><a href="./?month=<?= $month + 1 ?>">次の月&gt;&gt;</a></li>
            </ul>
        </div>

    </div>
</body>

</html>
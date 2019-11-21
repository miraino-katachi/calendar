<?php
    // 今月を0とする。
    $month = 0;
    // GETパラメータがあって、かつ、数値形式で、かつ、整数のとき
    // is_int()関数は、「数値文字列」の場合はfalseを返却します。
    // formやgetパラメータで受け取った「数値」は「文字列」になっています。
    if (isset($_GET['month']) && is_numeric($_GET['month']) && is_int((int)$_GET['month'])) {
        $month = (int)$_GET['month'];
    }

    // 当月の「ついたち」を求める。
    $dateTime = new DateTime(date('Y')."/".date("m")."/01");    // 現在月の「ついたち」
    if ($month > 0) {
        // $monthが0より大きいときは、現在月の「ついたち」に、その月数を追加する
        $dateTime->add(new DateInterval("P".$month."M"));
    } else {
        // $monthが0より小さいときは、現在月の「ついたち」から、その月数を引く
        $dateTime->sub(new DateInterval("P".(0 - $month)."M"));
    }

    // 月初の日の曜日＝当月の「ついたち」までに、その週に何日あるか
    $firstWeekday = $dateTime->format('w');

    // 月末の日＝当月に何日あるかの日数
    $dateTime->add(new DateInterval("P1M"));
    $dateTime->sub(new DateInterval("P1D"));
    $monthDays = $dateTime->format("d");

    // 当月に何週あるか（日数切り上げ）
    $weeks = ceil(($monthDays + $firstWeekday) / 7);

    // カレンダに記述する日付
    $date = 1;
?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8">
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
<?php
    // 当月にある週分繰り返し
    for ($week = 0; $week < $weeks; $week++) {
?>
<tr>
<?php
        // 一週間（7日分）繰り返し
        for ($day = 0; $day < 7; $day++) {
?>
    <td>
<?php
            if ($week == 0 && $day >= $firstWeekday) {
                // 月の1週目で、かつ、月初の日（曜日）以上のとき
                echo $date++;
            } elseif ($week > 0 && $date <= $monthDays) {
                // 月の2週目以降で、かつ、月末の日まで
                echo $date++;
            }
            // その他の日は何も表示しない
?>
    </td>
<?php
        }
?>
</tr>
<?php
    }
?>
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

<?php
// 今月を0とする。
$month = 0;

// GETパラメータがあって、かつ、数値形式で、かつ、整数のとき。
// isset()は、変数が存在していて、そして NULLとは異なるときにtrueを返却します。
// is_numeric()関数は、引数が「数値文字列」の場合にtrueを返却します。
// is_int()関数は、引数が「数値文字列」の場合はfalseを返却しますので、integerにキャストしています。
// HTMLのFORMからPOSTまたはGETで送信された値やgetパラメータ（クエリストリング、クエリパラメータ）で受け取った「数値」は「文字列」になっています。
if (isset($_GET['month']) && is_numeric($_GET['month']) && is_int((int) $_GET['month'])) {
    $month = (int) $_GET['month'];
}

// 今日の日付のDateTimeクラスのインスタンスを生成します。
// https://www.php.net/manual/ja/datetime.construct.php
$dateTime = new DateTime();

// タイムゾーンを「アジア/東京」にします。DateTimeZoneクラスを使用します。
// XAMPPのPHPのタイムゾーンは、デフォルトで「ヨーロッパ/ベルリン」になっています。
// https://www.php.net/manual/ja/datetimezone.construct.php
$dateTime->setTimezone(new DateTimeZone('Asia/Tokyo'));

// 今日の日付から(今日の日付 - 1)を引き、DateTimeクラスのインスタンスを今月の1日の日付に設定します。
// 日付のフォーマットの引数の書式は、こちらを参照してください。（date()関数と同じものが使えます。）
// https://www.php.net/manual/ja/function.date.php
$d = $dateTime->format('d');
$dateTime->sub(new DateInterval('P' . ($d - 1) . 'D'));

if ($month > 0) {
    // $monthが0より大きいときは、現在月の「ついたち」に、その月数を追加します。
    $dateTime->add(new DateInterval("P" . $month . "M"));
} else {
    // $monthが0より小さいときは、現在月の「ついたち」から、その月数を引きます。
    $dateTime->sub(new DateInterval("P" . (0 - $month) . "M"));
}

// 当月の「ついたち」が何曜日か求めます。当月の「ついたち」までに何日あるか、という日数と等しくなります。
$beginDayOfWeek = $dateTime->format('w');

// 当月に何日あるかの日数を求めます。
$monthDays = $dateTime->format('t');

// 当月に何週あるかを求めます。小数点以下を切り上げることで、同月の週数が求められます。
$weeks = ceil(($monthDays + $beginDayOfWeek) / 7);

// カレンダに記述する日付のカウンタ。
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
            <p><a href="./?month=<?= $month - 1 ?>">&lt;&lt;前の月</a></p>
            <p><a href="./">今月</a></p>
            <p><a href="./?month=<?= $month + 1 ?>">次の月&gt;&gt;</a></p>
        </div>
    </div>
</body>

</html>
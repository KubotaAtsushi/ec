<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="UTF-8">
        <title>管理者メニュー</title>
        <link rel="stylesheet" href="style.css">
    </head>
    <body>
        <!-- ビュー(V)-->
        <h1>管理者メニュー</h1>
        <?php if($flash_message !== null): ?>
        <P class="message"><?= $flash_message ?></P>
        <?php endif; ?>
        
        <h2><?= $login_user->name ?>さん、ようこそ！</h2>
      
        
        <p><a href="item_create.php">新規商品登録</a></p>
        <?php foreach($items as $item): ?>
        <?php if($item->status_flag === "0"): ?>
        <ul class="gray">
        <?php else: ?>
        <ul>
        <?php endif; ?>
            <li><a href="item_edit.php?id=<?= $item->id ?>"><?= $item->id ?></a></li>
            <li><?= $item->name ?></li>
            <li><?= $item->content ?></li>
            <li><?= $item->price ?>円</li>
            <li><?= $item->stock ?>個</li>
            <li><img src="upload/<?= $item->image ?>"></li>
            <li><?= $item->created_at ?></li>
            <li>
                <form action="status_flag_change.php" method="POST">
                    <input type="hidden" name="status_flag" value="<?= $item->status_flag ?>">
                    <input type="hidden" name="id" value="<?= $item->id ?>">
                    <?php if($item->status_flag === "0"): ?>
                    <button type="submit">公開にする</button>
                    <?php else: ?>
                    <button type="submit">非公開にする</button>
                    <?php endif; ?>
                </form>
            </li>
        </ul>
       
        <?php endforeach; ?>
        
        <p><a href="logout.php">ログアウト</a></p>

        <!--<p><a href="destroy.php">全ユーザー削除</a></p>-->
    </body>
</html>
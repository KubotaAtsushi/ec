<?php
    // モデル(M)
    require_once 'models/User.php';

    // カートの設計図を作成
    class Cart extends Model{
        // プロパティ
        public $id; // カート番号
        public $user_id; //登録者のユーザー番号
        public $item_id; // 商品番号
        public $number; // 個数
        public $created_at; // 公開日時
        public $updated_at; // 更新日時
        // コンストラクタ
        public function __construct($user_id="", $item_id="", $number=""){
            $this->user_id = $user_id;
            $this->item_id = $item_id;
            $this->number = $number;
        }
        
        // 入力チェックをするメソッド
        public function validate(){
            // 空のエラー配列作成
            $errors = array();
            // ユーザーIDが入力されていなければ
            if($this->user_id === ''){
                $errors[] = 'ユーザー番号が入力されていません';
            }
            // 商品idが入力されていなければ
            if(!preg_match('/^[1-9][0-9]*$/', $this->item_id)){
                $errors[] = '商品番号を入力してください';
            }
            // 個数が選択されていなければ
            if(!preg_match('/^[1-9][0-9]*$/', $this->number)){
                $errors[] = '個数を入力してください';
            }
            // 完成したエラー配列はいあげる
            return $errors;
        }
      
        // 全テーブル情報を取得するメソッド
        public static function all($user_id){
            try {
                $pdo = self::get_connection();
                $stmt = $pdo->prepare('SELECT carts.id, carts.item_id, items.name, items.image, items.price, items.stock, carts.number, carts.created_at FROM carts JOIN items on carts.item_id=items.id WHERE carts.user_id=:user_id');
                // バインド処理
                $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
                // 実行
                $stmt->execute();
                // フェッチの結果を、Cartクラスのインスタンスにマッピングする
                $stmt->setFetchMode(PDO::FETCH_CLASS|PDO::FETCH_PROPS_LATE, 'Cart');
                $carts = $stmt->fetchAll();
                self::close_connection($pdo, $stmt);
                // Cartクラスのインスタンスの配列を返す
                return $carts;
                
            } catch (PDOException $e) {
                return 'PDO exception: ' . $e->getMessage();
            }
        }
        
        // データを1件登録するメソッド
        public function save(){
            try {
                $pdo = self::get_connection();
                
                if($this->id === null){
                    $stmt = $pdo -> prepare("INSERT INTO carts (user_id, item_id, number) VALUES (:user_id, :item_id, :number)");
                    // バインド処理
                    $stmt->bindParam(':user_id', $this->user_id, PDO::PARAM_INT);
                    $stmt->bindParam(':item_id', $this->item_id, PDO::PARAM_INT);
                    $stmt->bindParam(':number', $this->number, PDO::PARAM_INT);
                    // 実行
                    $stmt->execute();
                    
                }else{
                     $stmt = $pdo -> prepare("UPDATE carts SET number=:number WHERE id=:id");
                     // バインド処理
                     $stmt->bindParam(':number', $this->number, PDO::PARAM_INT);
                     $stmt->bindParam(':id', $this->id, PDO::PARAM_INT);
                     // 実行
                     $stmt->execute();
                }
                
                self::close_connection($pdo, $stmt);
                if($this->id === null){
                    return "新規カートに追加しました。";
                }else{
                    return $this->id. 'の商品情報を更新しました';
                }
                
            } catch (PDOException $e) {
                return 'PDO exception: ' . $e->getMessage();
            }
        }
        
            public static function find($id){
                try {
                $pdo = self::get_connection();
                $stmt = $pdo -> prepare("select * from carts where id=:id");
                // バインド処理
                $stmt->bindParam(':id', $id, PDO::PARAM_INT);                // 実行
                $stmt->execute();
                // フェッチの結果を、Cartクラスのインスタンスにマッピングする
                $stmt->setFetchMode(PDO::FETCH_CLASS|PDO::FETCH_PROPS_LATE, 'Cart');
                $item = $stmt->fetch();
                self::close_connection($pdo, $stmt);
                return $item;
            } catch (PDOException $e) {
                return 'PDO exception: ' . $e->getMessage();
            }
        }

        // 重複チェック
        public static function find_my_cart($user_id, $item_id){
             try {
                $pdo = self::get_connection();
                $stmt = $pdo -> prepare("SELECT * FROM carts WHERE user_id=:user_id AND item_id=:item_id");
                // バインド処理
                $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
                $stmt->bindParam(':item_id', $item_id, PDO::PARAM_INT);

                // 実行
                $stmt->execute();
                // フェッチの結果を、Cartクラスのインスタンスにマッピングする
                $stmt->setFetchMode(PDO::FETCH_CLASS|PDO::FETCH_PROPS_LATE, 'Cart');
                $my_cart = $stmt->fetch();
                self::close_connection($pdo, $stmt);
                return $my_cart;                
            
            } catch (PDOException $e) {
                return 'PDO exception: ' . $e->getMessage();
            }
        }
        
        public static function destroy($id){
            try {
                $pdo = self::get_connection();
                $stmt = $pdo -> prepare("DELETE FROM carts WHERE id=:id");
                // バインド処理
                $stmt->bindParam(':id', $id, PDO::PARAM_INT);                
                // 実行
                $stmt->execute();
                self::close_connection($pdo, $stmt);
               
            } catch (PDOException $e) {
                    return 'PDO exception: ' . $e->getMessage();
            }
        }
    }

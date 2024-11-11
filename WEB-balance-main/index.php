<?php
session_start();
require_once ('conf.php');
global $connection;
$isLoggedIn = isset($_SESSION['user_id']);
if ($isLoggedIn) {
    $login = $_SESSION['user_id'];

    $kask = $connection->prepare("SELECT balance FROM agapov WHERE id=?;");
    $kask->bind_param('i', $login);
    $userData = null;
    $kask->execute();
    $kask->store_result();
    if ($kask->num_rows > 0) {
        $kask->bind_result($balance);
        while ($kask->fetch()) {
            $userData = [
                'balance' => $balance
            ];
        }
    }

    $cards_query = "SELECT title, description, artist, price, image_url FROM cards";
    $result = $connection->query($cards_query);
    }
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WEB-Balance</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: url('images/background.png') no-repeat center center fixed;
            background-size: cover;
            font-family: Arial, sans-serif;
            color: #fff;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 20px;
            background-color: rgba(0, 0, 0, 0.7);
            border-radius: 25px;
            margin: 20px auto auto auto;
            width: 1200px;
            height: 100px;
        }

        .header h1 {
            font-size: 3rem;
            color: #fff;
        }

        .header .buttons {
            margin-top: 2px;
        }

        .header .buttons button {
            border-radius: 20px;
            border: none;
            padding: 10px 20px;
            font-size: 1.2rem;
            margin: 5px;
        }

        .btn-custom1 {
            background-color: #fff;
            color: #000;
        }

        .btn-custom2 {
            background-color: #777;
            color: #fff;
        }

        .btn-custom1:hover, .btn-custom2:hover {
            background-color: black;
            color: white;
            cursor: pointer;
        }

        .cards-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-around;
            gap: 20px;
            padding: 20px;
        }

        .card {
            flex: 0 0 calc(33.33% - 120px);
            background-color: rgba(255, 255, 255, 0.2);
            border-radius: 20px;
            padding: 20px;
            text-align: center;
            width: 500px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.5);
            transition: transform 0.3s ease;
        }

        .card img {
            border-radius: 20px;
            max-width: 450px;
            height: 460px;
        }

        .card:hover {
            transform: scale(1.05);
        }

        .card-title {
            font-size: 1.5rem;
            margin: 10px 0;
        }

        .card-artist {
            font-size: 1rem;
            margin-bottom: 10px;
        }

        .card button {
            background-color: #000;
            color: #fff;
            border: none;
            padding: 10px;
            border-radius: 10px;
            cursor: pointer;
        }

        .card button:hover {
            background-color: #333;
        }
    </style>
</head>
<body>
<header class="header">
    <h1>WEB-Balance</h1>
    <div class="buttons">
        <?php if ($isLoggedIn):
            ?>
            <span id="user-balance">Баланс: <?php echo $userData['balance']; ?> евро</span>  <!-- Текущий баланс -->
            <button class="btn-custom1" onclick="window.location.href='balance.php'">Balance</button>
            <button class="btn-custom2" onclick="window.location.href='logout.php'">Logout</button>
        <?php else: ?>
            <button class="btn-custom1" onclick="window.location.href='register.php'">Register</button>
            <button class="btn-custom2" onclick="window.location.href='login.php'">Login</button>
        <?php endif; ?>
    </div>
</header>
<div class="cards-container">
    <?php while ($card = $result->fetch_assoc()): ?>
        <div class="card">
            <img src="images/<?php echo htmlspecialchars($card['image_url']); ?>" alt="<?php echo htmlspecialchars($card['title']); ?> album cover">
            <div class="card-title"><?php echo htmlspecialchars($card['title']); ?></div>
            <div class="card-artist"><?php echo htmlspecialchars($card['artist']); ?></div>
            <div class="price">Цена: <?php echo htmlspecialchars($card['price']); ?> евро</div>
            <button class="buy-btn" data-product="<?php echo htmlspecialchars($card['description']); ?>" data-price="<?php echo htmlspecialchars($card['price']); ?>">Купить</button>
        </div>
    <?php endwhile; ?>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        $('.buy-btn').click(function() {
            var product = $(this).data('product');
            var price = $(this).data('price');

            $.ajax({
                url: 'purchase.php',
                type: 'POST',
                data: {
                    product: product,
                    price: price
                },
                success: function(response) {
                    var data = JSON.parse(response);

                    if (data.status === 'success') {
                        alert(data.message);
                        $('#user-balance').text('Баланс: ' + data.new_balance + ' евро');
                    } else {
                        alert(data.message);
                    }
                },
                error: function() {
                    alert('Ошибка при выполнении покупки.');
                }
            });
        });
    });
</script>
</body>
</html>

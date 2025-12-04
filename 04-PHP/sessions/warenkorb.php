<?php
session_start();

// --- Produktkatalog (id => [name, preis]) ---
$products = [
    'apple'  => ['name' => 'Apfel 🍎',  'price' => 0.80],
    'banana' => ['name' => 'Banane 🍌', 'price' => 0.50],
    'coffee' => ['name' => 'Kaffee ☕', 'price' => 3.20],
    'bread'  => ['name' => 'Brot 🥖',   'price' => 2.40],
];

// --- Session initialisieren ---
$_SESSION['cart']  = $_SESSION['cart']  ?? [];             // ['productId' => qty]
$_SESSION['start'] = $_SESSION['start'] ?? time();         // Warenkorb-Startzeit

var_dump($_POST);
// --- Action-Handling (POST) ---
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    $id     = $_POST['id']     ?? '';

    // Schutz: Nur bekannte Produkt-IDs verarbeiten, wenn benötigt
    $isKnownProduct = $id && array_key_exists($id, $products);

    switch ($action) {
        case 'add':
            if ($isKnownProduct) {
                $n = $_POST[$id . '_number'];
                $_SESSION['cart'][$id] = ($_SESSION['cart'][$id] ?? 0) + $n;
            }
            break;

        case 'inc':
            if ($isKnownProduct && isset($_SESSION['cart'][$id])) {
                $_SESSION['cart'][$id]++;
            }
            break;

        case 'dec':
            if ($isKnownProduct && isset($_SESSION['cart'][$id])) {
                $_SESSION['cart'][$id]--;
                if ($_SESSION['cart'][$id] <= 0) {
                    unset($_SESSION['cart'][$id]);
                }
            }
            break;

        case 'remove':
            if ($isKnownProduct) {
                unset($_SESSION['cart'][$id]);
            }
            break;

        case 'clear':
            $_SESSION['cart'] = [];
            $_SESSION['start'] = time(); // Startzeit zurücksetzen
            break;

        case 'logout':
            session_unset(); // destroy $_SESSION
            session_destroy(); // destroy stored session data
            // neue Session direkt starten, damit die Seite weiter funktioniert
            session_start();
            $_SESSION['cart'] = [];
            $_SESSION['start'] = time();
            break;
    }
}

// --- Summen berechnen ---
$total = 0.0;
foreach ($_SESSION['cart'] as $pid => $qty) {
    if (isset($products[$pid])) {
        $total += $products[$pid]['price'] * $qty;
    }
}

// --- Formatierhilfe ---
function eur(float $v): string 
{ 
  return number_format($v, 2, ',', '.') . ' €'; 
}

$elapsed = time() - ($_SESSION['start'] ?? time());
$minutes = intdiv($elapsed, 60);
$seconds = $elapsed % 60;
?>
<!DOCTYPE html>
<html lang="de">
<head>
  <meta charset="UTF-8">
  <title>Mini-Warenkorb (PHP-Session)</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="warenkorb-styles.css">
  <script>
    function dec(id) {
      const input = document.getElementById(id);
      const value = Number(input.value);
      input.value = Math.max(1, value - 1); 
    }
    function inc(id) {
      const input = document.getElementById(id);
      const value = Number(input.value);
      input.value = value + 1; 
    }
  </script>
</head>
<body>
  <div class="wrap">
    <h1>🛒 Mini-Warenkorb mit PHP-Session</h1>
    <p class="meta">
      Warenkorb gestartet am <strong><?= date('d.m.Y H:i:s', $_SESSION['start']) ?></strong> —
      läuft seit <strong><?= $minutes ?> Min <?= $seconds ?> Sek</strong>.
    </p>

    <div class="grid">
      <!-- Linke Spalte: Produktkatalog -->
      <section class="card">
        <h2>Produkte</h2>
        <?php foreach ($products as $pid => $p) { 
          $numberKey = $pid . '_number';
        ?>
          <div class="product">
            <div>
              <div class="name"><?= $p['name'] ?></div>
              <div class="price"><?= eur($p['price']) ?></div>
            </div>
            <div class="qty">
              <form method="post" class="inline">
                <!-- Menge vermindern -->
                <button type="button" onclick="dec('<?=$numberKey?>')">–</button>

                <input type="number" readonly id="<?=$numberKey?>" name="<?=$numberKey?>" value="1">

                <!-- Menge erhöhen -->
                <button type="button" onclick="inc('<?=$numberKey?>')">+</button>

                <!-- in den Warenkorb -->
                <input type="hidden" name="action" value="add">
                <input type="hidden" name="id" value="<?= $pid ?>">
                <button class="btn btn-accent" type="submit">In den Warenkorb</button>
              </form>
            </div>
          </div>
        <?php } ?>
      </section>

      <!-- Rechte Spalte: Warenkorb -->
      <section class="card">
        <h2>Ihr Warenkorb <?= ' (' . count($_SESSION['cart']) . ' Artikel)'?></h2>

        <?php if (empty($_SESSION['cart'])): ?>
          <p class="muted">Der Warenkorb ist leer.</p>
        <?php else: ?>
          <?php foreach ($_SESSION['cart'] as $pid => $qty): if (!isset($products[$pid])) continue; ?>
            <?php $p = $products[$pid]; $line = $p['price'] * $qty; ?>
            <div class="product">
              <div>
                <div class="name"><?= $p['name'] ?></div>
                <div class="price"><?= eur($p['price']) ?> × <?= (int)$qty ?> = <strong><?= eur($line) ?></strong></div>
              </div>
              <div class="qty">
                <!-- Menge vermindern -->
                <form method="post" class="inline">
                  <input type="hidden" name="action" value="dec">
                  <input type="hidden" name="id" value="<?= $pid ?>">
                  <button type="submit">–</button>
                </form>

                <span><?= (int)$qty ?></span>

                <!-- Menge erhöhen -->
                <form method="post" class="inline">
                  <input type="hidden" name="action" value="inc">
                  <input type="hidden" name="id" value="<?= $pid ?>">
                  <button type="submit">+</button>
                </form>

                <!-- Position entfernen -->
                <form method="post" class="inline">
                  <input type="hidden" name="action" value="remove">
                  <input type="hidden" name="id" value="<?= $pid ?>">
                  <button class="btn btn-danger" type="submit">Entfernen</button>
                </form>
              </div>
            </div>
          <?php endforeach; ?>

          <div class="row">
            <div class="total">Gesamtsumme</div>
            <div class="total"><?= eur($total) ?></div>
          </div>
        <?php endif; ?>

        <div class="row" style="margin-top:16px;">
          <div class="actions">
            <form method="post" class="inline">
              <input type="hidden" name="action" value="clear">
              <button class="btn" type="submit">🧹 Warenkorb leeren</button>
            </form>

            <form method="post" class="inline">
              <input type="hidden" name="action" value="logout">
              <button class="btn btn-danger" type="submit">🚪 Session beenden</button>
            </form>
          </div>
        </div>
      </section>
    </div>
  </div>
</body>
</html>

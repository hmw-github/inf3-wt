<?php
    // Zeitzone für Datumsfunktionen setzen
    date_default_timezone_set('Europe/Berlin');

    /**
     * Hilfsfunktionen
     */
    
    /**
     * Liefert true, wenn $d im Format "YYYY-MM-DD" ist und ein gültiges Datum darstellt.
     */
    function is_valid_date(string $d): bool {
        if (! preg_match('/^\d{4}-\d{2}-\d{2}$/', $d)) {
            return false;
        }
        [$y, $m, $day] = array_map('intval', explode('-', $d));
        return checkdate($m, $day, $y);
    }

    /**
     * Liefert true, wenn $email eine gültige E-Mail-Adresse darstellt.
     */
    function is_valid_email(string $email) {
        return filter_var($email, FILTER_VALIDATE_EMAIL);
    }

    /**
     * Liefert true, wenn $s in eine ganze Zahl konvertierbar ist.
     */
    function is_valid_int(string $s) {
        return ctype_digit($s);
    }

    /**
     * Liefert $s zurück, wobei Zeilenvorschübe durch <br> ersetzt werden
     */
    function remove_newlines(string $s): string {
        return nl2br($s);
    }

    /**
     * Initialwerte
     */
    $name = $email = $ankunft = $abreise = $zimmerart = $nachricht = $personen = $datenschutz = '';
    $errors      = [];
    $success     = false;
    $zimmerOptions = [
        ''        => '— bitte wählen —',
        'einzel'  => 'Einzelzimmer',
        'doppel'  => 'Doppelzimmer',
        'familie' => 'Familienzimmer',
    ];

    /**
     * Verarbeitung
     */
    if (isset($_POST['absenden'])) {
        // Eingaben einlesen & trimmen
        $name         = trim($_POST['name']);
        $email        = trim($_POST['email']);
        $ankunft      = trim($_POST['ankunft']);
        $abreise      = trim($_POST['abreise']);
        $personen     = trim($_POST['personen']);
        $zimmerart    = trim($_POST['zimmerart']);
        $nachricht    = trim($_POST['nachricht']);
        $datenschutz  = $_POST['datenschutz'] ?? '';

        // Validierung: Name 
        if ($name === '') {
            $errors['name'] = 'Bitte geben Sie Ihren Namen ein.';
        } //___________
        //________________
        //________________

        // Validierung: E-Mail
        if ($email === '') {
            $errors['email'] = 'Bitte geben Sie Ihre E-Mail-Adresse ein.';
        } elseif (!is_valid_email($email)) {
            $errors['email'] = 'Bitte geben Sie eine gültige E-Mail-Adresse ein.';
        }

        // Validierung: Ankunft/Abreise (Datum)
        $today = strtotime('today');
        if ($ankunft === '' || !is_valid_date(($ankunft))) {
            $errors['ankunft'] = 'Bitte geben Sie ein gültiges Ankunftsdatum (YYYY-MM-DD) ein.';
        } else {
            $tsAnkunft = strtotime($ankunft);
            if ($tsAnkunft < $today) {
                $errors['ankunft'] = 'Das Ankunftsdatum darf nicht in der Vergangenheit liegen.';
            }
        }

        // Abreisedatum validieren
        if ($abreise === '' || !is_valid_date(($abreise))) {
            $errors['abreise'] = 'Bitte geben Sie ein gültiges Abreisedatum (YYYY-MM-DD) ein.';
        } elseif (!isset($errors['ankunft'])) {
            $tsAbreise = strtotime($abreise);
            if ($tsAbreise < $tsAnkunft) {
                $errors['abreise'] = 'Das Abreisedatum muss nach dem Ankunftsdatum liegen.';
            }
        }

        // Validierung: Personen (1–6)
        if ($personen === '') {
            $errors['personen'] = 'Bitte geben Sie die Personenanzahl an.';
        } elseif (!is_valid_int($personen)) {
            $errors['personen'] = 'Bitte geben Sie eine ganze Zahl an.';
        } else {
            $anz = (int) $personen;
            if ($anz < 1 || $anz > 6) {
                $errors['personen'] = 'Die Anzahl muss zwischen 1 und 6 liegen.';
            }
        }

        // Validierung: Zimmerart
        if ($zimmerart === '') {
            $errors['zimmerart'] = 'Bitte wählen Sie eine Zimmerart aus.';
        }

        // Validierung: Nachricht (optional, max. 300)
        if (strlen($nachricht) > 300) {
            $errors['nachricht'] = 'Die Nachricht ist zu lang (max. 300 Zeichen).';
        }

        // Validierung: Datenschutz
        if ($datenschutz === '') {
            $errors['datenschutz'] = 'Bitte stimmen Sie der Datenschutzerklärung zu.';
        }

        // Alles ok?
        if (empty($errors)) {
            $success = true;
        }
    }
?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Hotelzimmer-Anfrage</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h1>Hotelzimmer-Anfrage</h1>
    <p class="hint">Bitte füllen Sie das Formular aus. Pflichtfelder sind gekennzeichnet.</p>

    <?php if ($success) { ?>
        <div class="success">
            <strong>✅ Ihre Anfrage wurde erfolgreich übermittelt!</strong>
            <p>Wir melden uns zeitnah per E-Mail.</p>
        </div>

        <div class="summary">
            <h2>Zusammenfassung</h2>
            <p><strong>Name:</strong> <?= $name ?></p>
            <p><strong>E-Mail:</strong> <?= $email ?></p>
            <p><strong>Zeitraum:</strong> <?= $ankunft . ' - ' . $abreise ?></p>
            <p><strong>Personen:</strong> <?= $personen ?></p>
            <p><strong>Zimmerart:</strong> <?= $zimmerOptions[$zimmerart] ?></p>
            <?php if ($nachricht !== '') { ?>
                <p><strong>Nachricht:</strong> <?= remove_newlines($nachricht) ?></p>
            <?php } ?>
        </div>

        <div class="actions">
            <form method="post">
                <button>Neue Anfrage senden</button>
            </form>
        </div>
    <?php } else { ?>
        <form method="post" novalidate>
            <!-- Name -->
            <div class="field">
                <label for="name">Name *</label>
                <input type="text" id="name" name="name" value="<?= $name ?>" maxlength="100" required>
                <?php if (! empty($errors['name'])) { ?>
                    <p class="error"><?php echo $errors['name']?></p>
                <?php } ?>
            </div>

            <!-- E-Mail -->
            <div class="field">
                <label for="email">E-Mail *</label>
                <input type="email" id="email" name="email" value="<?php echo $email?>" required>
                <?php if (! empty($errors['email'])) { ?>
                    <p class="error"><?php echo $errors['email']?></p>
                <?php } ?>                
            </div>

            <!-- Ankunft -->
            <div class="field">
                <label for="ankunft">Ankunft *</label>
                <input type="date" id="ankunft" name="ankunft" value="<?php echo $ankunft?>" required>
                <?php if (! empty($errors['ankunft'])) { ?>
                    <p class="error"><?php echo $errors['ankunft']?></p>
                <?php } ?>
            </div>

            <!-- Abreise -->
            <div class="field">
                <label for="abreise">Abreise *</label>
                <input type="date" id="abreise" name="abreise" value="<?php echo $abreise?>" required>
                <?php if (! empty($errors['abreise'])) { ?>
                    <p class="error"><?php echo $errors['abreise']?></p>
                <?php } ?>
            </div>

            <!-- Personen -->
            <div class="field">
                <label for="personen">Personen (1–6) *</label>
                <input type="number" id="personen" name="personen" min="1" max="6" value="<?php echo $personen?>" required>
                <?php if (! empty($errors['personen'])) { ?>
                    <p class="error"><?php echo $errors['personen']?></p>
                <?php } ?>
            </div>

            <!-- Zimmerart -->
            <div class="field">
                <label for="zimmerart">Zimmerart *</label>
                <select id="zimmerart" name="zimmerart" required>
                    <?php
                        foreach ($zimmerOptions as $key => $value) {
                            $selected = $key == $zimmerart ? 'selected' : '';
                            echo '<option value="' . $key . '" '. $selected . '>' . $value . '</option>';
                        }
                    ?>
                </select>
                <?php if (! empty($errors['zimmerart'])) { ?>
                    <p class="error"><?php echo $errors['zimmerart']?></p>
                <?php } ?>
            </div>

            <!-- Nachricht -->
            <div class="field">
                <label for="nachricht">Nachricht (optional, max. 300 Zeichen)</label>
                <textarea id="nachricht" name="nachricht" rows="4" maxlength="300"><?php echo $nachricht?></textarea>
                <?php if (! empty($errors['nachricht'])) { ?>
                    <p class="error"><?php echo $errors['nachricht']?></p>
                <?php } ?>
            </div>

            <!-- Datenschutz -->
            <div class="field checkbox">
                <input type="checkbox" id="datenschutz" name="datenschutz" value="1" <?php echo $datenschutz === '1' ? 'checked' : ''?>>
                <label for="datenschutz">Ich stimme der Datenschutzerklärung zu. *</label>
            </div>
            <?php if (! empty($errors['datenschutz'])) { ?>
                <p class="error"><?php echo $errors['datenschutz']?></p>
            <?php } ?>
 
            <div class="actions">
                <input type="submit" name="absenden" value="Anfrage absenden">
            </div>
        </form>
    <?php } ?>
</body>
</html>
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
    if (___________________) {
        // Eingaben einlesen & trimmen
        $name        = _______________________________;
        ______________________________________________
        ______________________________________________
        ______________________________________________
        ______________________________________________
        ______________________________________________
        ______________________________________________
        ______________________________________________

        // Validierung: Name 
        if ($name === '') {
            $errors['name'] = 'Bitte geben Sie Ihren Namen ein.';
        } ___________
        ________________
        ________________

        // Validierung: E-Mail
        if ($email === '') {
            __________________________________________________
        } elseif (___________________________) {
            __________________________________________________
        }

        // Validierung: Ankunft/Abreise (Datum)
        $today = strtotime('today');
        if ($ankunft === '' || _________________________________) {
            $errors['ankunft'] = 'Bitte geben Sie ein gültiges Ankunftsdatum (YYYY-MM-DD) ein.';
        } else {
            $tsAnkunft = strtotime($ankunft);
            if ($tsAnkunft < $today) {
                $errors['ankunft'] = 'Das Ankunftsdatum darf nicht in der Vergangenheit liegen.';
            }
        }

        // Abreisedatum validieren
        _________________________________________
        _________________________________________
        _________________________________________
        _________________________________________
        _________________________________________
        _________________________________________
        _________________________________________
        _________________________________________
        _________________________________________
        
        // Validierung: Personen (1–6)
        if ($personen === '') {
            $errors['personen'] = 'Bitte geben Sie die Personenanzahl an.';
        } elseif (_________________________) {
            $errors['personen'] = 'Bitte geben Sie eine ganze Zahl an.';
        } else {
            $anz = (int) $personen;
            if (___________________________) {
                $errors['personen'] = 'Die Anzahl muss zwischen 1 und 6 liegen.';
            }
        }

        // Validierung: Zimmerart
        if (____________________________________________________________________) {
            $errors['zimmerart'] = 'Bitte wählen Sie eine Zimmerart aus.';
        }

        // Validierung: Nachricht (optional, max. 300)
        if (______________________________________________________________) {
            $errors['nachricht'] = 'Die Nachricht ist zu lang (max. 300 Zeichen).';
        }

        // Validierung: Datenschutz
        if (_____________________________________) {
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
    <link rel="stylesheet" href=""styles.css">
</head>
<body>
    <h1>Hotelzimmer-Anfrage</h1>
    <p class="hint">Bitte füllen Sie das Formular aus. Pflichtfelder sind gekennzeichnet.</p>

    <?php if ($success): ?>
        <div class="success">
            <strong>✅ Ihre Anfrage wurde erfolgreich übermittelt!</strong>
            <p>Wir melden uns zeitnah per E-Mail.</p>
        </div>

        <div class="summary">
            <h2>Zusammenfassung</h2>
            <p><strong>Name:</strong> _________________________________</p>
            <p><strong>E-Mail:</strong> _________________________________</p>
            <p><strong>Zeitraum:</strong> _________________________________</p>
            <p><strong>Personen:</strong> _________________________________</p>
            <p><strong>Zimmerart:</strong> _________________________________</p>
            <?php if ($nachricht !== '') { ?>
                <p><strong>Nachricht:</strong> _________________________________</p>
            <?php } ?>
        </div>

        <div class="actions">
            <form method="post">
                __________________________________________________
            </form>
        </div>
    <?php else { ?>
        <form method="post" novalidate>
            <!-- Name -->
            <div class="field">
                <label for="name">Name *</label>
                <input type="text" id="name" name="name" value="______________________" maxlength="100" required>
                ___________________________
                ___________________________
                ___________________________
            </div>

            <!-- E-Mail -->
            <div class="field">
                <label for="email">E-Mail *</label>
                <input type="email" id="email" name="email" value="<?php echo $email?>" required>
                ___________________________
                ___________________________
                ___________________________                
            </div>

            <!-- Ankunft -->
            <div class="field">
                <label for="ankunft">Ankunft *</label>
                <input type="date" id="ankunft" name="ankunft" value="<?php echo $ankunft?>" required>
                ___________________________
                ___________________________
                ___________________________
            </div>

            <!-- Abreise -->
            <div class="field">
                <label for="abreise">Abreise *</label>
                <input type="date" id="abreise" name="abreise" value="<?php echo $abreise?>" required>
                ___________________________
                ___________________________
                ___________________________
            </div>

            <!-- Personen -->
            <div class="field">
                <label for="personen">Personen (1–6) *</label>
                <input type="number" id="personen" name="personen" min="1" max="6" value="<?php echo $personen?>" required>
                ___________________________
                ___________________________
                ___________________________
            </div>

            <!-- Zimmerart -->
            <div class="field">
                <label for="zimmerart">Zimmerart *</label>
                <select id="zimmerart" name="zimmerart" required>
                ___________________________
                ___________________________
                ___________________________
                </select>
                ___________________________
                ___________________________
                ___________________________
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
            ___________________________
            ___________________________
            ___________________________

            <div class="actions">
                <input type="submit" name="absenden" value="Anfrage absenden">
            </div>
        </form>
    <?php } ?>
</body>
</html>
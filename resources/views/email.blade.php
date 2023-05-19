<!DOCTYPE html>
<html>

<head>
    <title>Ανανέωση Φιλοξενίας</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }

        .container {
            width: 80%;
            margin: 0 auto;
            padding: 20px;
        }

        .message {
            line-height: 1.6em;
        }

        .bank-details {
            margin-top: 20px;
            border-collapse: collapse;
            width: 100%;
        }

        .bank-details td,
        .bank-details th {
            border: 1px solid #ddd;
            padding: 8px;
        }

        .bank-details th {
            background-color: #f2f2f2;
            text-align: left;
        }

        .footer {
            margin-top: 50px;
        }
    </style>
</head>

<body>
    <div class="container">
        <h2>Αγαπητέ πελάτη,</h2>
        <p class="message">
            η ετήσια φιλοξενία για το {{ $hostingRow->{"Site Name"} }} λήγει στις {{ $hostingRow->{"Expiration Date"}
            }}.
            Για την ανανέωση του παρακαλώ προβείτε σε κατάθεση σε έναν από τους παρακάτω τραπεζικούς λογαριασμούς και
            στην συνέχεια στείλτε μας το αποδεικτικό της κατάθεσης στο support@dtek.gr :
        </p>
        <table class="bank-details">
            <thead>
                <tr>
                    <th>Τράπεζα</th>
                    <th>Επωνυμία</th>
                    <th>Αριθμός λογαριασμού</th>
                    <th>IBAN</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>ΤΡΑΠΕΖΑ WINBANK ΠΕΙΡΑΙΩΣ</td>
                    <td>ΜΟΡΑΣ ΙΩΑΝΝΗΣ</td>
                    <td>5209092099002</td>
                    <td>GR68 0172 2090 0052 0909 2099 002</td>
                </tr>
                <tr>
                    <td>ΤΡΑΠΕΖΑ WINBANK ΠΕΙΡΑΙΩΣ</td>
                    <td>Μόρας Ιωάννης</td>
                    <td>71400 23100 13582</td>
                    <td>GR78 0140 7140 7140 0231 0013 582</td>
                </tr>
                <tr>
                    <td>ΤΡΑΠΕΖΑ ΕΘΝΙΚΗ</td>
                    <td>ΜΟΡΑΣ ΙΩΑΝΝΗΣ ΤΟΥ ΔΗΜΗΤΡΙΟΥ</td>
                    <td>212/898780-72</td>
                    <td>GR0301102120000021289878072</td>
                </tr>
            </tbody>
        </table>
        <p class="footer">
            Ημερομηνία Λήξης: {{ $hostingRow->{"Expiration Date"} }} <br>
            Κόστος Ανανέωσης: €{{ $hostingRow->Cost }} (Συμπεριλαμβάνεται το ΦΠΑ) <br>
            Με την πραγματοποίηση της πληρωμής θα λάβετε στον ίδιο λογαριασμό email το αντίστοιχο παραστατικό. <br>
            Με την πάροδο της ημερομηνίας λήξης η υπηρεσία θα απενεργοποιηθεί αυτόματα. <br>
            Ευχαριστούμε, <br>
            Τμήμα Εξυπηρέτησης Πελατών <br>
            DTEK
        </p>
    </div>
</body>

</html>

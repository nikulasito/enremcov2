<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>ENREMCO Membership Application Form</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      line-height: 1.5;
      margin: 40px;
    }
    .header {
      text-align: center;
    }
    .header img {
      max-width: 100px;
      margin-bottom: 10px;
    }
    h1 {
      text-transform: uppercase;
      font-size: 30px;
      font-weight: bold;
      margin-bottom: 0;
    }
    .subtitle {
      font-size: 14px;
      margin-bottom: 30px;
    }
    .section {
      margin-bottom: 20px;
    }
    .section p {
      margin: 10px 0;
    }
    .bold {
      font-weight: bold;
    }
    .signature-section {
      display: flex;
      justify-content: space-between;
      margin-top: 40px;
    }
    .signature-block {
      text-align: center;
      width: 45%;
    }
    .signature-line {
      margin-top: 40px;
      border-top: 1px solid #000;
      width: 80%;
      margin-left: auto;
      margin-right: auto;
    }
    .text-center {
        text-align: center;
        margin-top: 5rem;
    }
  </style>
</head>
<body>

  <div class="header">
    <img src="http://152.42.222.178/assets/images/enremco_logo.png" alt="ENREMCO Logo">
    <h1>ENVIRONMENT AND NATURAL RESOURCES<br>EMPLOYEES MULTI-PURPOSE COOPERATIVE<br>(ENREMCO)</h1>
    <div class="subtitle">DENR-10, Compound Cagayan de Oro City</div>
  </div>

  <div class="section">
    <p><span class="bold">The Chairperson</span><br>ENREMCO</p>
    <p>Madam/Sir:</p>
    <p>
      I have the honor to apply as member of ENREMCO Region 10. If accepted, I shall pledge my loyalty to the Cooperative.
    </p>
  </div>

  <div class="section">
    <table class="table" style="width:100%;text-transform: uppercase;">
            <tr>
                <td style="width: 50%;"><strong>Name:</strong> {{ $member->name }}</td>
                <td style="width: 50%;"><strong>Email:</strong> {{ $member->email }}</td>
            </tr>
            <tr>
                <td style="width: 50%;"><strong>Office:</strong> {{ $member->office }}</td>
                <td style="width: 50%;"><strong>Home Address:</strong> {{ $member->address }}</td>
            </tr>
            <tr>
                <td style="width: 50%;"><strong>Religion:</strong> {{ $member->religion }}</td>
                <td style="width: 50%;"><strong>Sex:</strong> {{ $member->sex }}</td>
            </tr>
            <tr>
                <td style="width: 50%;"><strong>Marital Status:</strong> {{ $member->marital_status }}</td>
                <td style="width: 50%;"><strong>Annual Income:</strong> {{ $member->annual_income }}</td>
            </tr>
            <tr>
                <td style="width: 50%;"><strong>Beneficiaries:</strong> {{ $member->beneficiaries }}</td>
                <td style="width: 50%;"><strong>Birthdate:</strong> {{ $member->birthdate }}</td>
            </tr>
            <tr>
                <td style="width: 50%;"><strong>Education:</strong> {{ $member->education }}</td>
                <td style="width: 50%;"><strong>Member ID:</strong> {{ $member->employee_ID }}</td>
            </tr>
                </td>
            </tr>
        </table>
  </div>

  <div class="section">
    <p>
      I hereby authorize the Disbursing Office/Cashier to deduct from my salary the amount of Fifty Pesos (50.00 PESOS) as membership fee.
    </p>
    <p>
      Further authorize to deduct the amount of ₱_______ per month which is equivalent to 2% of my salary until the amount of _______ is fully satisfied, representing my subscribed Capital Stock of _______ shares.
    </p>
    <p>
      This application, where my signature is affixed, is my voluntary act and deed.
    </p>
    <p>
      This ______ day of ________, 2025.
    </p>
  </div>

  <div class="text-center">
    <p><strong style="text-transform: uppercase;">{{ $member->name }}</strong><br>Applicant</p>
  </div>

  <div class="signature-section">
    <div class="signature-block">
      Attested by:<br><br>
      <div class="signature-line"></div>
      Member
    </div>
    <div class="signature-block">
      Approved:<br><br>
      <div class="signature-line"></div>
      MARY GRACE O. ALEMANIO<br>
      Chairperson
    </div>
  </div>

</body>
</html>

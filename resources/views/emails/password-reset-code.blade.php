<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Code de réinitialisation</title>
    <style>
        body { font-family: Arial, sans-serif; background: #0f172a; margin: 0; padding: 40px 20px; }
        .container { max-width: 480px; margin: 0 auto; background: #1e293b; border-radius: 16px; padding: 40px; border: 1px solid #334155; }
        .logo { text-align: center; margin-bottom: 28px; }
        .logo img { height: 48px; }
        h1 { color: #f1f5f9; font-size: 20px; text-align: center; margin: 0 0 8px; }
        p { color: #94a3b8; font-size: 14px; text-align: center; line-height: 1.6; margin: 0 0 28px; }
        .code-box { background: #0f172a; border: 2px solid #ef4444; border-radius: 12px; padding: 24px; text-align: center; margin: 0 0 28px; }
        .code { font-size: 42px; font-weight: 900; letter-spacing: 12px; color: #ef4444; font-family: monospace; }
        .warning { background: #1e293b; border: 1px solid #334155; border-radius: 8px; padding: 12px 16px; }
        .warning p { color: #64748b; font-size: 12px; margin: 0; }
        .footer { text-align: center; margin-top: 28px; }
        .footer p { color: #475569; font-size: 11px; margin: 0; }
    </style>
</head>
<body>
    <div class="container">
        <div class="logo">
            <strong style="color:#ef4444; font-size:22px; letter-spacing:2px;">4K BROTHERS</strong>
        </div>

        <h1>Réinitialisation du mot de passe</h1>
        <p>Votre code de vérification à usage unique :<br>Valable pendant <strong style="color:#f1f5f9;">10 minutes</strong>.</p>

        <div class="code-box">
            <div class="code">{{ $code }}</div>
        </div>

        <div class="warning">
            <p>⚠️ Si vous n'avez pas demandé cette réinitialisation, ignorez cet email. Votre mot de passe ne sera pas modifié.</p>
        </div>

        <div class="footer">
            <p>© {{ date('Y') }} 4K BROTHERS — Pièces Auto Maroc</p>
        </div>
    </div>
</body>
</html>


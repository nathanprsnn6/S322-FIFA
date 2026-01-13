<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Réinitialisation de mot de passe FIFA</title>
</head>
<body style="margin: 0; padding: 0; background-color: #f4f6f8; font-family: Arial, sans-serif;">
    <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%">
        <tr>
            <td align="center" style="padding: 40px 0;">
                <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="600" style="background-color: #ffffff; border: 1px solid #e1e1e1; border-radius: 4px; box-shadow: 0 4px 15px rgba(0,0,0,0.05);">
                    <tr>
                        <td align="center" style="padding: 30px 0; background: linear-gradient(135deg, #053fc5 0%, #022b8a 100%);">
                            <h1 style="margin: 0; color: #ffffff; font-size: 28px; font-family: Verdana, sans-serif;">
                                FIFA<span style="color: #ffd700;">.com</span>
                            </h1>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding: 40px; color: #333333; text-align: left;">
                            <h2 style="color: #022b8a; margin-top: 0; font-size: 24px;">Réinitialisation de mot de passe</h2>
                            <div style="height: 3px; width: 40px; background-color: #ffd700; margin: 0 0 25px 0;"></div>
                            
                            <p style="font-size: 16px; line-height: 1.6; color: #555555;">
                                Bonjour <strong>{{ $utilisateur->prenom }}</strong>,
                            </p>
                            <p style="font-size: 16px; line-height: 1.6; color: #555555;">
                                Vous recevez cet e-mail car nous avons reçu une demande de réinitialisation de mot de passe pour votre compte FIFA officiel.
                            </p>

                            <table role="presentation" border="0" cellpadding="0" cellspacing="0" style="margin: 35px 0; width: 100%;">
                                <tr>
                                    <td align="center">
                                        <a href="{{ route('password.reset', ['token' => $token, 'courriel' => $utilisateur->courriel]) }}" 
                                           style="display: inline-block; padding: 15px 35px; background-color: #053fc5; color: #ffffff; text-decoration: none; font-weight: bold; border-radius: 50px; text-transform: uppercase;">
                                            Réinitialiser mon mot de passe
                                        </a>
                                    </td>
                                </tr>
                            </table>
                            
                            <p style="font-size: 13px; color: #999; text-align: center;">
                                Ce lien expirera dans 60 minutes. Si vous n'êtes pas à l'origine de cette demande, ignorez cet e-mail.
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
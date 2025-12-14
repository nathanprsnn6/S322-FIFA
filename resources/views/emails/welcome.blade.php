<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Finalisation de votre compte FIFA</title>
</head>
<body style="margin: 0; padding: 0; background-color: #f4f6f8; font-family: 'Arial', 'Helvetica', sans-serif;">

    <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%">
        <tr>
            <td align="center" style="padding: 40px 0;">
                
                <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="600" style="background-color: #ffffff; border: 1px solid #e1e1e1; border-radius: 4px; overflow: hidden; box-shadow: 0 4px 15px rgba(0,0,0,0.05);">
                    
                    <tr>
                        <td align="center" style="padding: 30px 0; background-color: #053fc5; background: linear-gradient(135deg, #053fc5 0%, #022b8a 100%);">
                            <h1 style="margin: 0; color: #ffffff; font-size: 28px; font-weight: bold; letter-spacing: 0.5px; font-family: 'Verdana', sans-serif;">
                                FIFA<span style="color: #ffd700;">.com</span>
                            </h1>
                        </td>
                    </tr>

                    <tr>
                        <td style="padding: 40px 40px; color: #333333; text-align: left;">
                            
                            <h2 style="color: #022b8a; margin-top: 0; font-size: 24px; font-weight: 700; margin-bottom: 20px;">
                                Finalisation de votre inscription
                            </h2>
                            
                            <div style="height: 3px; width: 40px; background-color: #ffd700; margin: 0 0 25px 0;"></div>
                            
                            <p style="font-size: 16px; line-height: 1.6; color: #555555; margin-bottom: 20px;">
                                Bonjour <strong>{{ $utilisateur->prenom }}</strong>,
                            </p>
                            
                            <p style="font-size: 16px; line-height: 1.6; color: #555555; margin-bottom: 20px;">
                                Merci d'avoir rejoint la communauté officielle FIFA. Pour activer pleinement votre accès à la billetterie, aux actualités exclusives et aux services FIFA+, nous avons besoin d'une dernière confirmation de votre part.
                            </p>
                            
                            <p style="font-size: 16px; line-height: 1.6; color: #555555; margin-bottom: 35px;">
                                Veuillez cliquer sur le bouton ci-dessous pour finaliser la création de votre compte officiel.
                            </p>

                            <table role="presentation" border="0" cellpadding="0" cellspacing="0" style="width: 100%;">
                                <tr>
                                    <td align="center">
                                        <a href="{{ url('/connexion') }}" style="display: inline-block; padding: 15px 35px; background-color: #053fc5; color: #ffffff; text-decoration: none; font-weight: bold; font-size: 16px; border-radius: 50px; text-transform: uppercase; border-bottom: 3px solid #022b8a;">
                                            Finaliser mon compte
                                        </a>
                                    </td>
                                </tr>
                            </table>
                            
                            <p style="font-size: 13px; color: #999; margin-top: 40px; text-align: center;">
                                Si vous n'êtes pas à l'origine de cette demande, vous pouvez ignorer cet email en toute sécurité.
                            </p>

                        </td>
                    </tr>

                    <tr>
                        <td align="center" style="padding: 30px 20px; background-color: #f8f9fa; color: #666666; font-size: 11px; border-top: 1px solid #eeeeee;">
                            
                            <p style="margin-bottom: 15px;">
                                <a href="#" style="color: #053fc5; text-decoration: none; margin: 0 10px; font-weight: bold;">Confidentialité</a> | 
                                <a href="#" style="color: #053fc5; text-decoration: none; margin: 0 10px; font-weight: bold;">Conditions d'utilisation</a> | 
                                <a href="#" style="color: #053fc5; text-decoration: none; margin: 0 10px; font-weight: bold;">Support</a>
                            </p>
                            
                            <p style="margin: 0 0 10px 0;">© 2025 FIFA. Tous droits réservés.</p>
                            <p style="margin: 0; color: #999999;">Fédération Internationale de Football Association<br>FIFA-Strasse 20, P.O. Box 8044 Zurich, Switzerland</p>
                        </td>
                    </tr>

                </table>
                
                <p style="margin-top: 20px; color: #999999; font-size: 12px;">
                    Ceci est un message automatique, merci de ne pas répondre.
                </p>

            </td>
        </tr>
    </table>

</body>
</html>
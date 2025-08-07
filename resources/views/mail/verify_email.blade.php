<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Verificação de E-mail</title>
</head>
<body style="margin: 0; padding: 0; background-color: #f4f4f4;">
    <table width="100%" cellpadding="0" cellspacing="0" border="0" align="center" bgcolor="#f4f4f4">
        <tr>
            <td align="center" style="padding: 40px 10px;">
                <table width="600" cellpadding="0" cellspacing="0" border="0" bgcolor="#ffffff" style="border-radius: 4px; padding: 20px;">
                    <tr>
                        <td style="font-family: Arial, sans-serif; font-size: 22px; font-weight: bold; color: #333333; padding-bottom: 20px;">
                            Olá, {{ $username }}!
                        </td>
                    </tr>
                    <tr>
                        <td style="font-family: Arial, sans-serif; font-size: 16px; color: #333333;">
                            Para confirmar seu e-mail e ativar sua conta, clique no botão abaixo:
                        </td>
                    </tr>
                    <tr>
                        <td align="center" style="padding: 30px 0;">
                            <a href="{{ $link }}" target="_blank" style="background-color: #28a745; color: #ffffff; text-decoration: none; padding: 12px 24px; font-size: 16px; border-radius: 4px; display: inline-block; font-family: Arial, sans-serif;">
                                Verificar E-mail
                            </a>
                        </td>
                    </tr>
                    <tr>
                        <td style="font-family: Arial, sans-serif; font-size: 14px; color: #666666;">
                            Se o botão não funcionar, copie e cole o link abaixo no seu navegador:
                        </td>
                    </tr>
                    <tr>
                        <td style="font-family: Arial, sans-serif; font-size: 14px; color: #555555; padding-top: 10px; word-break: break-all;">
                            {{ $link }}
                        </td>
                    </tr>
                    <tr>
                        <td style="font-family: Arial, sans-serif; font-size: 12px; color: #cccccc; padding-top: 30px;">
                            &copy; {{ date('Y') }} Seu Sistema. Todos os direitos reservados.
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>

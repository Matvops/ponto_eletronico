<div style="margin: 0; padding: 0; background-color: #f4f4f4;">
    <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%">
        <tr>
            <td align="center" style="padding: 40px 0;">
                <table border="0" cellpadding="0" cellspacing="0" width="600"
                    style="background-color: #ffffff; padding: 20px; border-radius: 6px;">
                    <tr>
                        <td
                            style="font-family: Arial, sans-serif; font-size: 24px; font-weight: bold; color: #333333; padding-bottom: 20px;">
                            Olá, {{ $username }}!
                        </td>
                    </tr>
                    <tr>
                        <td style="font-family: Arial, sans-serif; font-size: 16px; color: #333333;">
                            Você solicitou a recuperação de senha. Para continuar, utilize o código abaixo:
                        </td>
                    </tr>
                    <tr>
                        <td align="center" style="padding: 30px 0;">
                            <div
                                style="display: inline-block; background-color: #f0f0f0; border: 1px dashed #333333; padding: 15px 25px; font-size: 24px; font-weight: bold; letter-spacing: 4px; font-family: Arial, sans-serif;">
                                {{ $code }}
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td style="font-family: Arial, sans-serif; font-size: 14px; color: #777777;">
                            Se você não fez essa solicitação, ignore este e-mail. Nenhuma alteração será feita em sua
                            conta sem a confirmação do código.
                        </td>
                    </tr>
                    <tr>
                        <td style="font-family: Arial, sans-serif; font-size: 14px; color: #cccccc; padding-top: 30px;">
                            &copy; {{ date('Y') }} Seu Sistema. Todos os direitos reservados.
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</div>

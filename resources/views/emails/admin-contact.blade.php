<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $assunto }}</title>
</head>
<body style="margin: 0; padding: 0; background-color: #0a0f16; font-family: 'Helvetica Neue', Arial, sans-serif;">
    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="background-color: #0a0f16; padding: 40px 0;">
        <tr>
            <td align="center">
                <table role="presentation" width="600" cellspacing="0" cellpadding="0" style="max-width: 600px; width: 100%;">
                    {{-- Header --}}
                    <tr>
                        <td style="background: linear-gradient(135deg, #064e3b, #0a0f16); padding: 32px 40px; border-radius: 16px 16px 0 0; text-align: center;">
                            <h1 style="margin: 0; font-size: 28px; font-weight: 700; color: #ffffff; letter-spacing: 3px; text-transform: uppercase;">
                                LootBay
                            </h1>
                            <p style="margin: 8px 0 0; font-size: 12px; color: rgba(255,255,255,0.5); letter-spacing: 2px; text-transform: uppercase;">
                                Marketplace P2P
                            </p>
                        </td>
                    </tr>

                    {{-- Body --}}
                    <tr>
                        <td style="background-color: #111827; padding: 40px; border-left: 1px solid rgba(255,255,255,0.06); border-right: 1px solid rgba(255,255,255,0.06);">
                            <p style="margin: 0 0 8px; font-size: 14px; color: rgba(255,255,255,0.5); text-transform: uppercase; letter-spacing: 1.5px;">
                                Mensagem da Administração
                            </p>
                            <h2 style="margin: 0 0 24px; font-size: 22px; font-weight: 600; color: #ffffff;">
                                {{ $assunto }}
                            </h2>

                            <p style="margin: 0 0 16px; font-size: 15px; color: rgba(255,255,255,0.7); line-height: 1.7;">
                                Olá, <strong style="color: #34d399;">{{ $userName }}</strong>!
                            </p>

                            <div style="background-color: rgba(255,255,255,0.04); border: 1px solid rgba(255,255,255,0.08); border-radius: 12px; padding: 24px; margin: 24px 0;">
                                <p style="margin: 0; font-size: 15px; color: rgba(255,255,255,0.8); line-height: 1.8; white-space: pre-line;">{{ $mensagem }}</p>
                            </div>
                        </td>
                    </tr>

                    {{-- Footer --}}
                    <tr>
                        <td style="background-color: rgba(255,255,255,0.02); padding: 24px 40px; border-radius: 0 0 16px 16px; border: 1px solid rgba(255,255,255,0.06); border-top: none; text-align: center;">
                            <p style="margin: 0 0 8px; font-size: 13px; color: rgba(255,255,255,0.4);">
                                Este email foi enviado pela equipe <strong style="color: #34d399;">LootBay</strong>.
                            </p>
                            <p style="margin: 0; font-size: 12px; color: rgba(255,255,255,0.25);">
                                Se você não esperava esta mensagem, por favor desconsidere.
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>

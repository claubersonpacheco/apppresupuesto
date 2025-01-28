<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $data['title'] }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f8f9fa;
        }
        .header {
            text-align: center;
            padding: 20px;
            background-color: #e2e8f0;
        }
        .content {
            max-width: 600px;
            margin: 50px auto;
            padding: 20px;
            background-color: #ffffff;
            border: 1px solid #ddd;
        }
        .footer {
            max-width: 600px;
            margin: 50px auto;
            text-align: justify;
            font-size: 12px;
            color: #6c757d;
        }
        .footer a {
            color: #007bff;
            text-decoration: none;
        }
    </style>
</head>
<body>
<div class="header">
    @if($data['logo_impress'] != '')

        <img src="https://gestion.ferchecosolutions.com/img/logo-ifjreformas.png" alt="{{ $data['title'] }}" style="max-width: 300px; height: auto;">
    @else
        <h2 style="font-size: 20px; font-weight: bold; margin: 0;">{{ $data['title'] }}</h2>
    @endif
</div>
<div class="content">
    <p style="font-size: 14px; color: #555;">Este é um exemplo de email simples criado com HTML5. Lembre-se que emails reais devem ser testados em diferentes sistemas para garantir a entrega correta.</p>
    <p style="font-size: 14px; color: #555;">Saludos Cordiales,<br>{{ $data['title'] }}</p>
    <p style="font-size: 14px; color: #555;">Email: {{ $data['email'] }}<br>WhatsApp: {{ $data['whatsapp'] }}</p>
</div>
<div class="footer">
    <div class="">
        <p class="text-sm text-gray-500">Visite nuestro website: <a href="https://ifjreformas.es">https://ifjreformas.es</a></p>
    </div>
    <div class="">
        <p>Usted recibe esta comunicación desde <b>IFJ Reformas</b>, porque nos ha autorizado a enviarle información por web, correo electrónico, teléfono. Si quiere saber más sobre nosotros, puede ver haciendo clic <a href="https://ifjreformas.es"> aquí.</a></p>

        <br>

        <p>   AVISO LEGAL: Este mensaje y sus archivos adjuntos van dirigidos exclusivamente a su destinatario, pudiendo contener información confidencial sometida a secreto profesional. No está permitida su comunicación, reproducción o distribución sin la autorización expresa de <b>IFJ Reformas</b>, COMPAÑÍA DEL CONOCIMIENTO. Si usted no es el destinatario final, por favor, elimínelo e infórmenos por esta vía.
        </p>
        <br>
        <p>
            PROTECCIÓN DE DATOS: De conformidad con lo dispuesto en el Reglamento (UE) 2016/679, de 27 de abril (GDPR), y la Ley Orgánica 3/2018, de 5 de diciembre (LOPDGDD), le informamos de que los datos personales y la dirección de correo electrónico del interesado, se tratarán bajo la responsabilidad de <b>IFJ Reformas</b>, COMPAÑÍA DEL CONOCIMIENTO por un interés legítimo y para el envío de comunicaciones sobre nuestros productos y servicios, y se conservarán mientras ninguna de las partes se oponga a ello. Los datos podrán comunicarse a la empresas con el mismo fin antes descrito, cuya identidad podrá consultar en el siguiente enlace https://ifjreformas.es/empresa/. Le informamos de que puede ejercer los derechos de acceso, rectificación, portabilidad y supresión de sus datos y los de limitación y oposición a su tratamiento dirigiéndose ao correio eletronico: contacto@ifjreformas.es. Si considera que el tratamiento no se ajusta a la normativa vigente, podrá presentar una reclamación ante la autoridad de control en www.aepd.es. Datos de contacto del delegado de protección de datos: dpo@rbhglobal.com.
        </p>
    </div>

</div>
</body>
</html>

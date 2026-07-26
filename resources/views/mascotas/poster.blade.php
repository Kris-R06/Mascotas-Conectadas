<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        @page {
            margin: 30px;
        }
        body {
            font-family: 'Helvetica', Arial, sans-serif;
            color: #1e293b;
        }
        .contenedor {
            border: 6px solid #1d4ed8;
            border-radius: 12px;
            padding: 25px;
            text-align: center;
            background-color: #ffffff;
        }
        .titulo {
            font-size: 48px;
            font-weight: bold;
            color: #1d4ed8;
            letter-spacing: 2px;
            margin: 0 0 5px 0;
        }
        .subtitulo {
            font-size: 18px;
            color: #475569;
            margin-bottom: 20px;
        }

        /* ===== FOTO + DATOS (misma fila) ===== */
        .fila-principal {
            width: 100%;
            margin-top: 10px;
        }
        .celda-foto {
            width: 38%;
            vertical-align: top;
            text-align: center;
        }
        .celda-datos {
            width: 62%;
            vertical-align: top;
            text-align: left;
            padding-left: 20px;
        }
        .foto {
            width: 210px;
            height: 210px;
            object-fit: cover;
            border-radius: 10px;
            border: 3px solid #bfdbfe;
        }
        .nombre-mascota {
            font-size: 26px;
            font-weight: bold;
            color: #1e3a8a;
            margin: 12px 0 0 0;
            text-align: center;
        }

        .tabla-datos {
            width: 100%;
            border-collapse: collapse;
        }
        .tabla-datos td {
            padding: 9px 10px;
            font-size: 14px;
            border-bottom: 1px solid #dbeafe;
            text-align: left;
        }
        .tabla-datos td.etiqueta {
            font-weight: bold;
            color: #1d4ed8;
            width: 40%;
        }

        /* ===== DESCRIPCIÓN ===== */
        .descripcion-box {
            border: 2px solid #bfdbfe;
            border-radius: 12px;
            padding: 15px 18px;
            margin-top: 20px;
            text-align: left;
            background-color: #f8fafc;
        }
        .descripcion-titulo {
            font-size: 15px;
            font-weight: bold;
            color: #1d4ed8;
            margin: 0 0 8px 0;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .descripcion-texto {
            font-size: 14px;
            color: #334155;
            line-height: 1.5;
            margin: 0;
        }

        /* ===== CONTACTO ===== */
        .contacto {
            background-color: #eff6ff;
            border: 1px solid #dbeafe;
            border-radius: 10px;
            padding: 15px;
            margin-top: 18px;
        }
        .contacto-titulo {
            font-size: 16px;
            font-weight: bold;
            color: #1d4ed8;
            margin-bottom: 8px;
        }
        .contacto-dato {
            font-size: 15px;
            margin: 3px 0;
            color: #1e293b;
        }
        .qr {
            width: 100px;
            height: 100px;
            margin-top: 15px;
        }
        .footer {
            font-size: 11px;
            color: #94a3b8;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="contenedor">

        <p class="titulo">¡SE BUSCA!</p>
        <p class="subtitulo">Mascota extraviada — Ayúdanos a encontrarla</p>

        <!-- Foto + Datos en la misma fila -->
        <table class="fila-principal">
            <tr>
                <td class="celda-foto">
                    @if(isset($fotoBase64) && $fotoBase64)
                        <img src="{{ $fotoBase64 }}" class="foto">
                    @endif
                    <p class="nombre-mascota">{{ $mascota->nombre }}</p>
                </td>
                <td class="celda-datos">
                    <table class="tabla-datos">
                        <tr>
                            <td class="etiqueta">Especie</td>
                            <td>{{ $mascota->especie->nombre ?? 'No especificado' }}</td>
                        </tr>
                        <tr>
                            <td class="etiqueta">Raza</td>
                            <td>{{ $mascota->raza }}</td>
                        </tr>
                        <tr>
                            <td class="etiqueta">Color</td>
                            <td>{{ $mascota->color }}</td>
                        </tr>
                        <tr>
                            <td class="etiqueta">Edad aproximada</td>
                            <td>{{ $mascota->edad }}</td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>

        <div class="descripcion-box">
            <p class="descripcion-titulo">Descripción</p>
            <p class="descripcion-texto">{{ $mascota->descripcion }}</p>
        </div>

        <div class="contacto">
            <p class="contacto-titulo">Si lo ves, contáctanos:</p>
            <p class="contacto-dato">{{ $mascota->user->name }}</p>
            <p class="contacto-dato">Tel: {{ $mascota->user->telefono }}</p>

            @if($mascota->qr)
                <img src="{{ $qrUrl }}" class="qr">
            @endif
        </div>

        <p class="footer">Generado desde Mascotas Conectadas — {{ now()->format('d/m/Y') }}</p>

    </div>
</body>
</html>
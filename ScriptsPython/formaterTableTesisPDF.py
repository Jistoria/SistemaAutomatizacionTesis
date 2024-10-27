import pdfplumber
import re
import json

def extract_data_from_pdf(pdf_path):
    data = []
    with pdfplumber.open(pdf_path) as pdf:
        for page in pdf.pages:
            # Extraer tablas en cada página
            tables = page.extract_tables()
            if tables:
                for table in tables:
                    # Aquí filtramos las filas para que contengan los datos correctos, por ejemplo, verificamos cédulas (solo números)
                    for row in table:
                        if re.match(r'^\d{10,13}$', row[0]):  # Verifica si es un número de cédula válido
                            student_data = {
                                'cedula': row[0],
                                'apellidos_nombres': row[1],
                                'periodo_cohorte': row[2],
                                'opcion_aprobacion': row[3],
                                'tema': row[4],
                                'tutor': row[5],
                                'observacion': row[6]
                            }
                            data.append(student_data)
    return data

# Especifica la ruta del PDF
pdf_path = '/ruta/al/pdf/PlantillaIngresoDatos.pdf'

# Extrae los datos
involucrados_data = extract_data_from_pdf(pdf_path)

# Guarda los datos como JSON para enviarlos al backend (Laravel)
with open('involucrados_data.json', 'w') as outfile:
    json.dump(involucrados_data, outfile)

print(f"Datos extraídos y guardados en involucrados_data.json")

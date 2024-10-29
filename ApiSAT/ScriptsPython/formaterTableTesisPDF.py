import pdfplumber
import json
import re
import sys

def extract_period_data(pdf_path):
    data = {
        'period_academic': '',
        'start_date': '',
        'end_date': '',
        'degree': ''
    }

    with pdfplumber.open(pdf_path) as pdf:
        for page in pdf.pages:
            text = page.extract_text()

            # Buscar el Periodo Académico
            period_match = re.search(r'Periodo académico\s*([^\n]+)', text)
            if period_match:
                data['period_academic'] = period_match.group(1).strip()

            degree_match = re.search(r'Carrera\s*([^\n]+)', text)
            if degree_match:
                data['degree'] = degree_match.group(1).strip()

            # Buscar la Fecha de inicio
            start_date_match = re.search(r'Fecha de inicio\s*([^\n]+)', text)
            if start_date_match:
                data['start_date'] = start_date_match.group(1).strip()

            # Buscar la Fecha de cierre
            end_date_match = re.search(r'Fecha de cierre\s*([^\n]+)', text)
            if end_date_match:
                data['end_date'] = end_date_match.group(1).strip()

            # Detenerse en la primera página porque los datos están ahí
            break

    return data


def extract_students_data(pdf_path):
    students = []

    with pdfplumber.open(pdf_path) as pdf:
        for page in pdf.pages:
            tables = page.extract_tables()
            if tables:
                for table in tables:
                    for row in table:
                        # Verificar que row[0] no sea None antes de usar re.match
                        if row[0] is not None and re.match(r'^\d{10}$', row[0]):
                            # Combinar el nombre completo si está dividido en múltiples líneas
                            student_name = ' '.join(row[1].splitlines()) if row[1] is not None else ''
                            thesis_title = ' '.join(row[4].splitlines()) if row[4] is not None else ''
                            tutor_name = ' '.join(row[5].splitlines()) if row[5] is not None else ''

                            student = {
                                'student_dni': row[0],
                                'student_name': student_name,
                                'period': row[2] if row[2] is not None else '',
                                'thesis_title': thesis_title,
                                'tutor_name': tutor_name,
                                'observation': row[6] if len(row) > 6 and row[6] is not None else None
                            }
                            students.append(student)

    return students

if __name__ == '__main__':
    # Recibir el path del archivo PDF como argumento
    pdf_path = sys.argv[1]

    # Extraer datos del periodo académico
    period_data = extract_period_data(pdf_path)

    # Extraer datos de los estudiantes
    students_data = extract_students_data(pdf_path)

    # Combinar ambos conjuntos de datos en un solo objeto JSON
    data = {
        'period_academic': period_data['period_academic'],
        'start_date': period_data['start_date'],
        'end_date': period_data['end_date'],
        'degree': period_data['degree'],
        'students': students_data
    }

    # Imprimir el objeto JSON
    print(json.dumps(data, ensure_ascii=False))

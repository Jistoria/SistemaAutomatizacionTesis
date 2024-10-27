import pdfplumber
import json
import re
import sys

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
                            student = {
                                'student_dni': row[0],
                                'student_name': row[1] if row[1] is not None else '',  # Manejar si el nombre es None
                                'period': row[2] if row[2] is not None else '',
                                'thesis_title': row[4] if row[4] is not None else '',
                                'tutor_name': row[5] if row[5] is not None else '',
                                'observation': row[6] if len(row) > 6 and row[6] is not None else None
                            }
                            students.append(student)

    return students

if __name__ == '__main__':
    pdf_path = sys.argv[1]
    students_data = extract_students_data(pdf_path)
    print(json.dumps(students_data))

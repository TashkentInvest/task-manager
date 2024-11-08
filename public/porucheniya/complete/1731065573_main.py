import pdfkit

# Path to your wkhtmltopdf executable
config = pdfkit.configuration(wkhtmltopdf=r'C:\Program Files\wkhtmltopdf\bin\wkhtmltopdf.exe')

# Path to your HTML file and output PDF file
input_html = 'index.htm'
output_pdf = 'output.pdf'

# Convert HTML to PDF
pdfkit.from_file(input_html, output_pdf, configuration=config)

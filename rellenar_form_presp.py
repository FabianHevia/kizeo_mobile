from flask import Flask, send_file
from openpyxl import load_workbook
from io import BytesIO

app = Flask(__name__)

# Función para rellenar el archivo de Excel
def rellenar_excel(datos):
    # Cargar la plantilla existente
    archivo = "ncaso_-_Datos_Evaluadores.xlsx"
    wb = load_workbook(archivo)
    hoja = wb.active  # Asume que los datos están en la hoja activa

    # Rellenar campos con los datos
    hoja["B5"] = datos["nombre"]
    hoja["B6"] = datos["rut"]
    hoja["B7"] = datos["direccion"]
    hoja["B8"] = datos["comuna"]
    hoja["B9"] = datos["ciudad"]

    #Fachada Frontal

    hoja["B14"] = datos["repararFachadaFrontal"]
    hoja["B15"] = datos["repararFachadaTrasera"]
    hoja["B16"] = datos["repararFachadaLateralIzquierdo"]
    hoja["B17"] = datos["repararFachadaLateralDerecho"]

    hoja["C14"] = datos["anchoFachadaFrontal"]
    hoja["C15"] = datos["anchoFachadaTrasera"]
    hoja["C16"] = datos["anchoFachadaLateralIzquierdo"]
    hoja["C17"] = datos["anchoFachadaLateralDerecho"]

    hoja["D14"] = datos["altoFachadaFrontal"]
    hoja["D15"] = datos["altoFachadaTrasera"]
    hoja["D16"] = datos["altoFachadaLateralIzquierdo"]
    hoja["D17"] = datos["altoFachadaLateralDerecho"]

    hoja["E14"] = datos["fisuraFachadaFrontal"]
    hoja["E15"] = datos["fisuraFachadaTrasera"]
    hoja["E16"] = datos["fisuraFachadaLateralIzquierdo"]
    hoja["E17"] = datos["fisuraFachadaLateralDerecho"]

    hoja["F14"] = datos["materialFachadaFrontal"]
    hoja["F15"] = datos["materialFachadaTrasera"]
    hoja["F16"] = datos["materialFachadaLateralIzquierdo"]
    hoja["F17"] = datos["materialFachadaLateralDerecho"]

    hoja["G14"] = datos["recubrimientoFachadaFrontal"]
    hoja["G15"] = datos["recubrimientoFachadaTrasera"]
    hoja["G16"] = datos["recubrimientoFachadaLateralIzquierdo"]
    hoja["G17"] = datos["recubrimientoFachadaLateralDerecho"]

    #Techumbre
    hoja["J14"] = datos["repararTechumbre"]
    hoja["K14"] = datos["materialTechumbre"]

    hoja["I16"] = datos["anchoTechumbre"]
    hoja["K16"] = datos["largoTechumbre"]

    hoja["V20"] = datos["reacomodotejasTechumbre"]
    hoja["W20"] = datos["mtsTechumbre"]

    #Cierre Perimetral
    hoja["N14"] = datos["repararCierrePerimetral"]
    hoja["O14"] = datos["fisuraCierrePerimetral"]
    hoja["P14"] = datos["reemplazopanderetasCierrePerimetral"]
    hoja["Q14"] = datos["cantidadpanderetasCierrePerimetral"]
    hoja["R14"] = datos["reemplazopilaresCierrePerimetral"]
    hoja["S16"] = datos["cantidadpilaresCierrePerimetral"]

    hoja["M16"] = datos["anchoCierrePerimetral"]
    hoja["O16"] = datos["altoCierrePerimetral"]
    hoja["P16"] = datos["recubrimientoCierrePerimetral"]

    #Puertas
    hoja["V14"] = datos["repararMarcoPuerta"]
    hoja["W14"] = datos["fisuraMarcoPuerta"]
    hoja["X14"] = datos["reemplazoPuerta"]
    hoja["Y14"] = datos["cantidadPuerta"]
    hoja["Z14"] = datos["reemplazoCerradura"]
    hoja["AA14"] = datos["cantidadPilares"]

    #Primera Posicion Recinto B21 hasta R21

    #Dimensiones
    hoja["B21"] = datos["anchoRecinto"]
    hoja["C21"] = datos["largoRecinto"]
    hoja["D21"] = datos["altoRecinto"]

    #Daño Cielo
    hoja["E21"] = datos["repararCieloRecinto"]
    hoja["F21"] = datos["fisuraCieloRecinto"]
    hoja["G21"] = datos["materialCieloRecinto"]
    hoja["H21"] = datos["recubrimientoCieloRecinto"]

    #Daño Muro
    hoja["I21"] = datos["repararMuroRecinto"]
    hoja["J21"] = datos["fisuraMuroRecinto"]
    hoja["K21"] = datos["materialMuroRecinto"]
    hoja["L21"] = datos["recubrimientoMuroRecinto"]

    #Daño Piso
    hoja["I21"] = datos["repararPisoRecinto"]
    hoja["J21"] = datos["fisuraPisoRecinto"]
    hoja["L21"] = datos["recubrimientoPisoRecinto"]
    
    # Guardar el archivo en memoria para no escribirlo en disco
    archivo_salida = BytesIO()
    wb.save(archivo_salida)
    archivo_salida.seek(0)  # Volver al inicio del archivo en memoria

    return archivo_salida

# Ruta de la app Flask para descargar el archivo
@app.route('/descargar-excel')
def descargar_excel():
    # Ejemplo de datos que quieres rellenar
    datos = {
        "nombre": "Fabián Hevia",
        "rut": "21.154.622-0",
        "direccion": "German Garcés",
        "comuna": "Maipú",
        "ciudad": "Santiago",
        "repararFachadaFrontal": "no",
        "anchoFachadaFrontal": "",
        "altoFachadaFrontal": "",
        "fisuraFachadaFrontal": "",
        "materialFachadaFrontal": "",
        "recubrimientoFachadaFrontal": "",
        "repararFachadaTrasera": "no",
        "anchoFachadaTrasera": "",
        "altoFachadaTrasera": "",
        "fisuraFachadaTrasera": "",
        "materialFachadaTrasera": "",
        "recubrimientoFachadaTrasera": "",
        "repararFachadaLateralIzquierdo": "no",
        "anchoFachadaLateralIzquierdo": "",
        "altoFachadaLateralIzquierdo": "",
        "fisuraFachadaLateralIzquierdo": "",
        "materialFachadaLateralIzquierdo": "",
        "recubrimientoFachadaLateralIzquierdo": "",
        "repararFachadaLateralRecubrimiento": "no",
        "anchoFachadaLateralRecubrimiento": "",
        "altoFachadaLateralRecubrimiento": "",
        "fisuraFachadaLateralRecubrimiento": "",
        "materialFachadaLateralRecubrimiento": "",
        "recubrimientoFachadaLateralRecubrimiento": "",

    }

    # Llamar a la función para generar el Excel
    archivo_excel = rellenar_excel(datos)

    # Enviar el archivo para descargar
    return send_file(
        archivo_excel,
        mimetype='application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        as_attachment=True,
        download_name='1_-_Datos_Evaluadores.xlsx'
    )

if __name__ == '__main__':
    app.run(debug=True)


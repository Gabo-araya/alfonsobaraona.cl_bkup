// JavaScript Document
// Modificado por Gabriel Araya - http://es.geocities.com/gabrielarayarocha/
// Modificación: ingreso de verificación de carácteres numéricos 
// Autor original:
// Abril 2001, David Hernández Sanz
// http://www.terra.es/personal3/davidhdezsanz/
// Validacion de distintos tipos de campos de formulario:
// - Texto no nulo
// - Direccion de correo electronico (e-mail): alfanum@alfanum.alfanum[.alfanum], donde alfanum son caracteres alfanumericos u otros (pasados como parametro)
// - Direccion en Internet (URL)
// Para ello no se utilizan expresiones regulares.
//
//Este script y otros muchos pueden
//descarse on-line de forma gratuita
//en El Código: www.elcodigo.com

/* dice si cadena es texto no vacio o no                                     */
function vacio(cadena)
  {                                    // DECLARACION DE CONSTANTES
    var blanco = " \n\t" + String.fromCharCode(13); // blancos
                                       // DECLARACION DE VARIABLES
    var i;                             // indice en cadena
    var es_vacio;                      // cadena es vacio o no
    for(i = 0, es_vacio = true; (i < cadena.length) && es_vacio; i++) // INICIO
      es_vacio = blanco.indexOf(cadena.charAt(i)) != - 1;
    return(es_vacio);
  }


/* dice si cadena es un email (alfanum@alfanum.alfanum[.alfanum]) o no, donde */
/* alfanum son caracteres alfanumericos u otros                           */
function email(cadena, otros)
  {                                    // DECLARACION-INICIALIZACION VARIABLES
    var i, j;                          // indice en cadena
    var es_email = 0 < cadena.length;  // cadena es email o no
    i = salta_alfanumerico(cadena, 0, otros); // INICIO
    if(es_email = 0 < i)               // lee "alfanum*"
      if(es_email = (i < cadena.length))
        if(es_email = cadena.charAt(i) == '@') // lee "alfanum@*"
          {
            i++;
            j = salta_alfanumerico(cadena, i, otros);
            if(es_email = i < j)       // lee "alfanum@alfanum*"
              if(es_email = j < cadena.length)
                if(es_email = cadena.charAt(j) == '\.')
                  {                    // lee "alfanum@alfanum.*"
                    j++;
                    i = salta_alfanumerico(cadena, j, otros);
                    if(es_email = j < i) // lee "alfanum@alfanum.alfanum*"
                      while(es_email && (i < cadena.length))
                        if(es_email = cadena.charAt(i) == '\.')
                          {
                            i++;
                            j = salta_alfanumerico(cadena, i, otros);
                            if(es_email = i < j) // lee "alfanum@alfanum.alfanum[.alfanum]*"
                              i = j;
                          }
                  }
          }
    return(es_email);
  }


// salta caracteres alfanumericos y otros a partir de cadena[i]  y  da siguiente posicion 

function salta_alfanumerico(cadena, i, otros)
  {                                    // DECLARACION DE VARIABLES
    var j;                             // indice en cadena
    var car;                           // caracter de cadena
    var alfanum;                       // cadena[j] es alfanumerico u otros
    for(j = i, alfanum = true; (j < cadena.length) && alfanum; j++) // INICIO
      {
        car = cadena.charAt(j);
        alfanum = alfanumerico(car) || (otros.indexOf(car) != -1);
      }
    if(!alfanum)                       // lee "alfanumX"
      j--;
    return(j);
  }
  
/* dice si car es alfanumerico                                               */
function alfanumerico(car)
  {
    return(alfabetico(car) || numerico(car));
  }


/* dice si car es alfabetico                                                 */
function alfabetico(car)               // DECLARACION DE CONSTANTES
  {                                    // caracteres alfabeticos
    var alfa = "ABCDEFGHIJKLMNOPQRSTUWXYZabcdefghijklmnopqrstuvxyz";
    return(alfa.indexOf(car) != - 1);  // INICIO
  }


/* dice si car es numerico                                                   */
function numerico(car)
  {                                    // DECLARACION DE CONSTANTES
    var num = "0123456789";            // caracteres numericos
    return(num.indexOf(car) != - 1);   // INICIO
  }


//validación para el número telefónico
//indica si sólo son números
var numb = '0123456789';

function isValid(parm,val) 
  {
    if (parm == "") return (true);
    for (i=0; i<parm.length; i++) {
      if (val.indexOf(parm.charAt(i),0) == -1) 
	  return (false);
      }
    return (true);
  }

function isNum(parm) 
  {
    return isValid(parm,numb);
  }

function stripBlanks(fld) 
  {
	var result = "";
	var c = 0;
	for (i=0; i<fld.length; i++) {
      if (fld.charAt(i) != " " || c > 0) {
	    result += fld.charAt(i);
		if (fld.charAt(i) != " ") c = result.length;
		}
    }
    return result.substr(0,c);
  }

function validField(fld) 
  {
    fld = stripBlanks(fld);
    if (!isNum(fld)) return (false); // test numeric
    return (true);
  }

// ejemplo validacion formulario

function ValidaCampos(form)
  {
    if(vacio(form.nombre_.value)) 
	  {
	  alert("Ingrese su nombre por favor.");
	  return(false);
	  }
	else if(vacio(form.telefono_.value)) 
	  {
      alert("Ingrese su teléfono por favor. \nSin espacios ni guiones.");
	  return(false);
	  }
	else if(!validField(form.telefono_.value)) 
	  {
      alert("Ingrese un teléfono válido por favor. \nSin espacios ni guiones.");
	  return(false);
	  }
	else if(!email(form.correo_.value, "-_")) 
	  {
      alert("Ingrese una dirección de correo electrónico válida, por favor.");
	  return(false);
	  }
	else if(vacio(form.mensaje_.value)) 
	  {
      alert("No se puede enviar un correo sin mensaje. \nIngrese su mensaje por favor.");
	  return(false);
	  }
	else
      //sustituir esta linea por return(true) para hacer el submit de un formulario real
	  //alert("Datos en proceso de envío...");
	  return(true);
      //return(false);
  }
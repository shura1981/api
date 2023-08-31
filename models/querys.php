<?php
class QUERYS
{
public static function joinClientesInactivos($id)
{
return  "SELECT DISTINCT T0.\"CardCode\"  FROM \"ELITE_NUTRITION\".\"OCRD\" T0   
INNER JOIN \"ELITE_NUTRITION\".\"OSLP\" T1 ON T0.\"SlpCode\" = T1.\"SlpCode\" 
INNER JOIN \"ELITE_NUTRITION\".\"OPLN\" T2 ON T0.\"ListNum\" = T2.\"ListNum\"        
WHERE T0.\"CardCode\"   LIKE 'C%' and  T0.\"SlpCode\"  = '$id' and T0.\"validFor\" = 'Y'";
}


public static function ClientesInactivos($id_cliente, $ini, $fin)
{
return "SELECT DISTINCT T0.\"CardCode\" FROM \"ELITE_NUTRITION\".\"OINV\"  T0                 
WHERE T0.\"CardCode\" = '$id_cliente' and T0.\"DocDate\" between '$ini' and '$fin'";
}

public static function sellerv2_Block1($ini, $fin, $id)
{
return "
SELECT DISTINCT T0.\"SeriesName\", T1.\"DocDate\", T1.\"DocNum\", T7.\"BaseRef\",T1.\"CardCode\", T2.\"CardName\",T1.\"Address2\", T2.\"E_Mail\", T2.\"City\",T10.\"ListName\", T2.\"Phone1\", T11.\"Name\" AS \"Analista Comercial\",T5.\"U_NAME\",  T7.\"LineTotal\" AS \"TOTAL\", T6.\"Descript\", T7.\"LineNum\", T7.\"ItemCode\", T7.\"Dscription\", T7.\"Quantity\"*-1 AS \"Cantidad\", T9.\"ItmsGrpNam\" AS \"Categoria\", T7.\"WhsCode\", T7.\"DiscPrcnt\" FROM \"ELITE_NUTRITION\".\"NNM1\"  T0 
LEFT JOIN \"ELITE_NUTRITION\".\"OINV\"  T1 ON T0.\"Series\" = T1.\"Series\" 
LEFT  JOIN \"ELITE_NUTRITION\".\"OCRD\"  T2 ON T1.\"CardCode\" = T2.\"CardCode\" 
LEFT JOIN \"ELITE_NUTRITION\".\"CRD1\" T3 ON T2.\"CardCode\" = T3.\"CardCode\" AND T1.\"ShipToCode\" = T3.\"Address\"
LEFT JOIN \"ELITE_NUTRITION\".\"OSLP\" T4 ON T1.\"SlpCode\" = T4.\"SlpCode\" 
LEFT  JOIN \"ELITE_NUTRITION\".\"OUSR\" T5 ON T1.\"UserSign\" = T5.\"USERID\" 
LEFT JOIN \"ELITE_NUTRITION\".\"OPYM\" T6 ON T1.\"PeyMethod\" = T6.\"PayMethCod\" 
LEFT JOIN \"ELITE_NUTRITION\".\"INV1\" T7 ON T1.\"DocEntry\" = T7.\"DocEntry\"
LEFT JOIN \"ELITE_NUTRITION\".\"OITM\" T8 ON T7.\"ItemCode\" = T8.\"ItemCode\" 
LEFT JOIN \"ELITE_NUTRITION\".\"OITB\" T9 ON T8.\"ItmsGrpCod\" = T9.\"ItmsGrpCod\"
INNER JOIN \"ELITE_NUTRITION\".\"OPLN\" T10 ON T2.\"ListNum\" = T10.\"ListNum\"
LEFT JOIN \"ELITE_NUTRITION\".\"@ENG_VENDEDORES\" T11 ON T3.\"U_ENG_BraOffSeller\" = T11.\"Code\"
WHERE  T1.\"DocDate\" BETWEEN '$ini' AND '$fin' AND T1.\"CANCELED\" = 'N' AND T3.\"U_ENG_BraOffSeller\" = '$id'        
";
}
public static function sellerv2_Block2($ini, $fin, $id)
{
return "
SELECT DISTINCT T0.\"SeriesName\", T1.\"DocDate\", T1.\"DocNum\", T7.\"BaseRef\",T1.\"CardCode\", T2.\"CardName\",T1.\"Address2\", T2.\"E_Mail\", T2.\"City\",T10.\"ListName\", T2.\"Phone1\", T11.\"Name\" AS \"Analista Comercial\",T5.\"U_NAME\",  T7.\"LineTotal\" *-1 AS \"TOTAL\", T6.\"Descript\", T7.\"LineNum\", T7.\"ItemCode\", T7.\"Dscription\", T7.\"Quantity\"*-1 AS \"Cantidad\", T9.\"ItmsGrpNam\" AS \"Categoria\", T7.\"WhsCode\", T7.\"DiscPrcnt\" FROM \"ELITE_NUTRITION\".\"NNM1\"  T0 
LEFT JOIN \"ELITE_NUTRITION\".\"ORIN\"  T1 ON T0.\"Series\" = T1.\"Series\" 
LEFT  JOIN \"ELITE_NUTRITION\".\"OCRD\"  T2 ON T1.\"CardCode\" = T2.\"CardCode\" 
LEFT JOIN \"ELITE_NUTRITION\".\"CRD1\" T3 ON T2.\"CardCode\" = T3.\"CardCode\" AND T1.\"ShipToCode\" = T3.\"Address\"
LEFT JOIN \"ELITE_NUTRITION\".\"OSLP\" T4 ON T1.\"SlpCode\" = T4.\"SlpCode\" 
LEFT  JOIN \"ELITE_NUTRITION\".\"OUSR\" T5 ON T1.\"UserSign\" = T5.\"USERID\" 
LEFT JOIN \"ELITE_NUTRITION\".\"OPYM\" T6 ON T1.\"PeyMethod\" = T6.\"PayMethCod\" 
LEFT JOIN \"ELITE_NUTRITION\".\"RIN1\" T7 ON T1.\"DocEntry\" = T7.\"DocEntry\"
LEFT JOIN \"ELITE_NUTRITION\".\"OITM\" T8 ON T7.\"ItemCode\" = T8.\"ItemCode\" 
LEFT JOIN \"ELITE_NUTRITION\".\"OITB\" T9 ON T8.\"ItmsGrpCod\" = T9.\"ItmsGrpCod\"
INNER JOIN \"ELITE_NUTRITION\".\"OPLN\" T10 ON T2.\"ListNum\" = T10.\"ListNum\"
LEFT JOIN \"ELITE_NUTRITION\".\"@ENG_VENDEDORES\" T11 ON T3.\"U_ENG_BraOffSeller\" = T11.\"Code\"
WHERE  T1.\"DocDate\" BETWEEN '$ini' AND '$fin' AND T1.\"CANCELED\" = 'N' AND T3.\"U_ENG_BraOffSeller\" = '$id' 
";
}
public static function sellerv2_Block3($ini, $fin, $id)
{
return "
SELECT DISTINCT T1.\"DocNum\", T7.\"LineTotal\" AS \"TOTAL\", T7.\"Dscription\" FROM \"ELITE_NUTRITION\".\"NNM1\"  T0 
LEFT JOIN \"ELITE_NUTRITION\".\"ORDR\"  T1 ON T0.\"Series\" = T1.\"Series\" 
LEFT JOIN \"ELITE_NUTRITION\".\"OCRD\"  T2 ON T1.\"CardCode\" = T2.\"CardCode\" 
LEFT JOIN \"ELITE_NUTRITION\".\"CRD1\" T3 ON T2.\"CardCode\" = T3.\"CardCode\"  AND T1.\"ShipToCode\" = T3.\"Address\"
LEFT JOIN \"ELITE_NUTRITION\".\"OSLP\" T4 ON T1.\"SlpCode\" = T4.\"SlpCode\" 
LEFT JOIN \"ELITE_NUTRITION\".\"OUSR\" T5 ON T1.\"UserSign\" = T5.\"USERID\" 
LEFT JOIN \"ELITE_NUTRITION\".\"OPYM\" T6 ON T1.\"PeyMethod\" = T6.\"PayMethCod\" 
LEFT JOIN \"ELITE_NUTRITION\".\"RDR1\" T7 ON T1.\"DocEntry\" = T7.\"DocEntry\" 
LEFT JOIN \"ELITE_NUTRITION\".\"OITM\" T8 ON T7.\"ItemCode\" = T8.\"ItemCode\" 
LEFT JOIN \"ELITE_NUTRITION\".\"OITB\" T9 ON T8.\"ItmsGrpCod\" = T9.\"ItmsGrpCod\"
INNER JOIN \"ELITE_NUTRITION\".\"OPLN\" T10 ON T2.\"ListNum\" = T10.\"ListNum\"
LEFT JOIN \"ELITE_NUTRITION\".\"@ENG_VENDEDORES\" T11 ON T3.\"U_ENG_BraOffSeller\" = T11.\"Code\"
WHERE  T1.\"DocDate\" BETWEEN '$ini' AND '$fin' AND T1.\"CANCELED\" = 'N' AND T3.\"U_ENG_BraOffSeller\" = '$id' AND T1.\"U_ENG_Motivo_Cierro\" = 'NA'
";
}

public static function datosOrdenes($ini, $fin, $id)
{
return "  
SELECT DISTINCT T1.\"DocNum\",T1.\"CardCode\", T2.\"ListNum\",  T2.\"City\", T1.\"CardName\", T7.\"LineTotal\" AS \"TOTAL\", T7.\"Dscription\" FROM \"ELITE_NUTRITION\".\"NNM1\"  T0 
LEFT JOIN \"ELITE_NUTRITION\".\"ORDR\"  T1 ON T0.\"Series\" = T1.\"Series\" 
LEFT JOIN \"ELITE_NUTRITION\".\"OCRD\"  T2 ON T1.\"CardCode\" = T2.\"CardCode\" 
LEFT JOIN \"ELITE_NUTRITION\".\"CRD1\" T3 ON T2.\"CardCode\" = T3.\"CardCode\"  AND T1.\"ShipToCode\" = T3.\"Address\"
LEFT JOIN \"ELITE_NUTRITION\".\"OSLP\" T4 ON T1.\"SlpCode\" = T4.\"SlpCode\" 
LEFT JOIN \"ELITE_NUTRITION\".\"OUSR\" T5 ON T1.\"UserSign\" = T5.\"USERID\" 
LEFT JOIN \"ELITE_NUTRITION\".\"OPYM\" T6 ON T1.\"PeyMethod\" = T6.\"PayMethCod\" 
LEFT JOIN \"ELITE_NUTRITION\".\"RDR1\" T7 ON T1.\"DocEntry\" = T7.\"DocEntry\" 
LEFT JOIN \"ELITE_NUTRITION\".\"OITM\" T8 ON T7.\"ItemCode\" = T8.\"ItemCode\" 
LEFT JOIN \"ELITE_NUTRITION\".\"OITB\" T9 ON T8.\"ItmsGrpCod\" = T9.\"ItmsGrpCod\"
INNER JOIN \"ELITE_NUTRITION\".\"OPLN\" T10 ON T2.\"ListNum\" = T10.\"ListNum\"
LEFT JOIN \"ELITE_NUTRITION\".\"@ENG_VENDEDORES\" T11 ON T3.\"U_ENG_BraOffSeller\" = T11.\"Code\"
WHERE  T1.\"DocDate\" BETWEEN '$ini' AND '$fin' AND T1.\"CANCELED\" = 'N' AND T3.\"U_ENG_BraOffSeller\" = '$id' AND T1.\"U_ENG_Motivo_Cierro\" = 'NA'
";
}
public static function ventasTotalLinea($ini, $fin, $id)
{
return "SELECT T0.\"DocNum\" FROM \"ELITE_NUTRITION\".\"OINV\" T0 WHERE T0.\"DocDate\" BETWEEN '$ini' AND '$fin' AND T0.\"SlpCode\"  = '$id'";
}
public static function sqlNumd($ini, $fin, $id)
{
return  "
SELECT T1.\"DocNum\" FROM \"ELITE_NUTRITION\".\"ORIN\"  T1
WHERE  T1.\"DocDate\" BETWEEN '$ini' AND '$fin' AND T1.\"CANCELED\" = 'N' AND T1.\"SlpCode\" = '$id'
";
}
public static function SQLNUMO($ini, $fin, $id)
{
return "
SELECT T1.\"DocNum\" FROM \"ELITE_NUTRITION\".\"ORDR\"  T1
WHERE  T1.\"DocDate\" BETWEEN '$ini' AND '$fin' AND T1.\"CANCELED\" = 'N' AND T1.\"SlpCode\" = '$id'
";
}

public static function block_numerics_one($id)
{
return "
SELECT DISTINCT T0.\"CardCode\" FROM \"ELITE_NUTRITION\".\"OCRD\" T0  
INNER JOIN \"ELITE_NUTRITION\".\"OSLP\" T1 ON T0.\"SlpCode\" = T1.\"SlpCode\" 
WHERE T0.\"CardCode\"   LIKE 'C%' and  T0.\"SlpCode\"  = '$id' and T0.\"validFor\" = 'Y'
";

}


public static function block_numerics_two($id, $item2, $fechaI, $fechaF)
{
return "
SELECT SUM(T1.\"Quantity\") AS \"TOTAL\" FROM \"ELITE_NUTRITION\".\"OINV\" T0 
INNER JOIN \"ELITE_NUTRITION\".\"INV1\" T1 ON T0.\"DocEntry\" = T1.\"DocEntry\" 
LEFT JOIN \"ELITE_NUTRITION\".\"CRD1\" T2 ON T0.\"CardCode\" = T2.\"CardCode\"  AND T0.\"ShipToCode\" = T2.\"Address\"
LEFT JOIN \"ELITE_NUTRITION\".\"@ENG_VENDEDORES\" T3 ON T2.\"U_ENG_BraOffSeller\" = T3.\"Code\"
WHERE T1.\"ItemCode\" = '$item2' AND T3.\"Code\" = '$id' AND T0.\"DocDate\" BETWEEN '$fechaI' AND '$fechaF'
";
}

public static function block_numerics_three($id, $item2, $fechaI, $fechaF)
{
return "
SELECT DISTINCT T0.\"CardCode\" FROM \"ELITE_NUTRITION\".\"OINV\" T0 
INNER JOIN \"ELITE_NUTRITION\".\"INV1\" T1 ON T0.\"DocEntry\" = T1.\"DocEntry\" 
LEFT JOIN \"ELITE_NUTRITION\".\"CRD1\" T2 ON T0.\"CardCode\" = T2.\"CardCode\"  AND T0.\"ShipToCode\" = T2.\"Address\"
LEFT JOIN \"ELITE_NUTRITION\".\"@ENG_VENDEDORES\" T3 ON T2.\"U_ENG_BraOffSeller\" = T3.\"Code\"
WHERE T1.\"ItemCode\" = '$item2' AND T3.\"Code\" = '$id' AND T0.\"DocDate\" BETWEEN '$fechaI' AND '$fechaF'
";
}

public static function block_numerics_four($id_cliente, $item2, $fechaI, $fechaF)
{
return "
SELECT DISTINCT T0.\"CardCode\" FROM \"ELITE_NUTRITION\".\"OINV\"  T0 
WHERE T0.\"CardCode\" = '$id_cliente' and T0.\"DocDate\" between '$fechaI' and '$fechaF'
";
}


public static function superReport($ini, $fin){
    return "
    SELECT DISTINCT  T0.\"SeriesName\", T1.\"DocDate\", T1.\"isIns\", T1.\"DocNum\",'' AS\"BaseRef\", T2.\"U_ENG_Agrupado\" AS \"Agrupado por\",
     T1.\"CardCode\", T2.\"CardName\",T1.\"Address2\", T2.\"E_Mail\", T2.\"City\",T10.\"ListName\",T2.\"Phone1\", T11.\"Name\" AS \"Analista Comercial\",T5.\"U_NAME\", 
    T7.\"LineTotal\" AS \"TOTAL\", T6.\"Descript\", T7.\"LineNum\",  T7.\"ItemCode\", T7.\"Dscription\", T7.\"Quantity\" AS \"Cantidad\", T7.\"OpenCreQty\" AS \"Cant Pendiente\",
     T9.\"ItmsGrpNam\" AS \"Categoria\", T7.\"WhsCode\", T7.\"DiscPrcnt\", T1.\"DiscPrcnt\", T2.\"CreateDate\",T1.\"U_ENG_Medio_Pago\" AS \"Medio de Pgago\",
     T12.\"GroupName\" AS \"Tipologia\", T1.\"BaseAmnt\", T1.\"VatSum\", T1.\"TotalExpns\", T1.\"WTSum\", T1.\"U_SECUENCIA\" ,T7.\"TaxCode\", T1.\"U_ESTADO_AUTORIZACIO\",
     T2.\"CreditLine\", T1.\"DocDueDate\",T7.\"GrossBuyPr\"
    FROM \"ELITE_NUTRITION\".\"NNM1\"  T0
    LEFT JOIN \"ELITE_NUTRITION\".\"OINV\"  T1 ON T0.\"Series\" = T1.\"Series\"
    LEFT JOIN \"ELITE_NUTRITION\".\"OCRD\"  T2 ON T1.\"CardCode\" = T2.\"CardCode\"
    LEFT JOIN \"ELITE_NUTRITION\".\"CRD1\" T3 ON T2.\"CardCode\" = T3.\"CardCode\"  AND T1.\"ShipToCode\" = T3.\"Address\"
    LEFT JOIN \"ELITE_NUTRITION\".\"OSLP\" T4 ON T1.\"SlpCode\" = T4.\"SlpCode\"
    LEFT JOIN \"ELITE_NUTRITION\".\"OUSR\" T5 ON T1.\"UserSign\" = T5.\"USERID\"
    LEFT JOIN \"ELITE_NUTRITION\".\"OPYM\" T6 ON T1.\"PeyMethod\" = T6.\"PayMethCod\"
    LEFT JOIN \"ELITE_NUTRITION\".\"INV1\" T7 ON T1.\"DocEntry\" = T7.\"DocEntry\"
    LEFT JOIN \"ELITE_NUTRITION\".\"OITM\" T8 ON T7.\"ItemCode\" = T8.\"ItemCode\"
    LEFT JOIN \"ELITE_NUTRITION\".\"OITB\" T9 ON T8.\"ItmsGrpCod\" = T9.\"ItmsGrpCod\"
    INNER JOIN \"ELITE_NUTRITION\".\"OPLN\" T10 ON T2.\"ListNum\" = T10.\"ListNum\"
    LEFT JOIN \"ELITE_NUTRITION\".\"@ENG_VENDEDORES\" T11 ON T3.\"U_ENG_BraOffSeller\" = T11.\"Code\"
    INNER JOIN \"ELITE_NUTRITION\".\"OCRG\" T12 ON T2.\"GroupCode\" = T12.\"GroupCode\"
     WHERE  T1.\"DocDate\" >= '$ini' AND T1.\"DocDate\" <='$fin' AND T1.\"CANCELED\" = 'N'  
    UNION
    SELECT DISTINCT T0.\"SeriesName\", T1.\"DocDate\", T1.\"isIns\",T1.\"DocNum\", T7.\"BaseRef\", T2.\"U_ENG_Agrupado\" AS \"Agrupado por\", T1.\"CardCode\", T2.\"CardName\",T1.\"Address2\", T2.\"E_Mail\", T2.\"City\",T10.\"ListName\", T2.\"Phone1\", T11.\"Name\" AS \"Analista Comercial\",T5.\"U_NAME\",  T7.\"LineTotal\" *-1 AS \"TOTAL\", T6.\"Descript\", T7.\"LineNum\", T7.\"ItemCode\", T7.\"Dscription\", T7.\"Quantity\"*-1 AS \"Cantidad\", T7.\"OpenCreQty\", T9.\"ItmsGrpNam\" AS \"Categoria\", T7.\"WhsCode\", T7.\"DiscPrcnt\", T1.\"DiscPrcnt\", T2.\"CreateDate\",T1.\"U_ENG_Medio_Pago\" AS \"Medio de Pgago\", T12.\"GroupName\" AS \"Tipologia\", T1.\"BaseAmnt\", T1.\"VatSum\", T1.\"TotalExpns\", T1.\"WTSum\", T1.\"U_SECUENCIA\" , T7.\"TaxCode\", T1.\"U_ESTADO_AUTORIZACIO\", T2.\"CreditLine\",  T1.\"DocDueDate\", T7.\"GrossBuyPr\"
    FROM \"ELITE_NUTRITION\".\"NNM1\"  T0
    LEFT JOIN \"ELITE_NUTRITION\".\"ORIN\"  T1 ON T0.\"Series\" = T1.\"Series\"
    LEFT  JOIN \"ELITE_NUTRITION\".\"OCRD\"  T2 ON T1.\"CardCode\" = T2.\"CardCode\"
    LEFT JOIN \"ELITE_NUTRITION\".\"CRD1\" T3 ON T2.\"CardCode\" = T3.\"CardCode\" AND T1.\"ShipToCode\" = T3.\"Address\"
    LEFT JOIN \"ELITE_NUTRITION\".\"OSLP\" T4 ON T1.\"SlpCode\" = T4.\"SlpCode\"
    LEFT  JOIN \"ELITE_NUTRITION\".\"OUSR\" T5 ON T1.\"UserSign\" = T5.\"USERID\"
    LEFT JOIN \"ELITE_NUTRITION\".\"OPYM\" T6 ON T1.\"PeyMethod\" = T6.\"PayMethCod\"
    LEFT JOIN \"ELITE_NUTRITION\".\"RIN1\" T7 ON T1.\"DocEntry\" = T7.\"DocEntry\"
    LEFT JOIN \"ELITE_NUTRITION\".\"OITM\" T8 ON T7.\"ItemCode\" = T8.\"ItemCode\"
    LEFT JOIN \"ELITE_NUTRITION\".\"OITB\" T9 ON T8.\"ItmsGrpCod\" = T9.\"ItmsGrpCod\"
    INNER JOIN \"ELITE_NUTRITION\".\"OPLN\" T10 ON T2.\"ListNum\" = T10.\"ListNum\"
    LEFT JOIN \"ELITE_NUTRITION\".\"@ENG_VENDEDORES\" T11 ON T3.\"U_ENG_BraOffSeller\" = T11.\"Code\"
    INNER JOIN \"ELITE_NUTRITION\".\"OCRG\" T12 ON T2.\"GroupCode\" = T12.\"GroupCode\"
     WHERE  T1.\"DocDate\" >='$ini' AND T1.\"DocDate\" <= '$fin' AND T1.\"CANCELED\" = 'N'      ORDER BY T0.\"SeriesName\", T1.\"DocNum\"
    ";
    
    
    
    } 

public static function superReportP($ini, $fin, $producto){
return "
SELECT DISTINCT  T0.\"SeriesName\", T1.\"DocDate\", T1.\"isIns\", T1.\"DocNum\",'' AS\"BaseRef\", T2.\"U_ENG_Agrupado\" AS \"Agrupado por\",
 T1.\"CardCode\", T2.\"CardName\",T1.\"Address2\", T2.\"E_Mail\", T2.\"City\",T10.\"ListName\",T2.\"Phone1\", T11.\"Name\" AS \"Analista Comercial\",T5.\"U_NAME\", 
T7.\"LineTotal\" AS \"TOTAL\", T6.\"Descript\", T7.\"LineNum\",  T7.\"ItemCode\", T7.\"Dscription\", T7.\"Quantity\" AS \"Cantidad\", T7.\"OpenCreQty\" AS \"Cant Pendiente\",
 T9.\"ItmsGrpNam\" AS \"Categoria\", T7.\"WhsCode\", T7.\"DiscPrcnt\", T1.\"DiscPrcnt\", T2.\"CreateDate\",T1.\"U_ENG_Medio_Pago\" AS \"Medio de Pgago\",
 T12.\"GroupName\" AS \"Tipologia\", T1.\"BaseAmnt\", T1.\"VatSum\", T1.\"TotalExpns\", T1.\"WTSum\", T1.\"U_SECUENCIA\" ,T7.\"TaxCode\", T1.\"U_ESTADO_AUTORIZACIO\",
 T2.\"CreditLine\", T1.\"DocDueDate\",T7.\"GrossBuyPr\"
FROM \"ELITE_NUTRITION\".\"NNM1\"  T0
LEFT JOIN \"ELITE_NUTRITION\".\"OINV\"  T1 ON T0.\"Series\" = T1.\"Series\"
LEFT JOIN \"ELITE_NUTRITION\".\"OCRD\"  T2 ON T1.\"CardCode\" = T2.\"CardCode\"
LEFT JOIN \"ELITE_NUTRITION\".\"CRD1\" T3 ON T2.\"CardCode\" = T3.\"CardCode\"  AND T1.\"ShipToCode\" = T3.\"Address\"
LEFT JOIN \"ELITE_NUTRITION\".\"OSLP\" T4 ON T1.\"SlpCode\" = T4.\"SlpCode\"
LEFT JOIN \"ELITE_NUTRITION\".\"OUSR\" T5 ON T1.\"UserSign\" = T5.\"USERID\"
LEFT JOIN \"ELITE_NUTRITION\".\"OPYM\" T6 ON T1.\"PeyMethod\" = T6.\"PayMethCod\"
LEFT JOIN \"ELITE_NUTRITION\".\"INV1\" T7 ON T1.\"DocEntry\" = T7.\"DocEntry\"
LEFT JOIN \"ELITE_NUTRITION\".\"OITM\" T8 ON T7.\"ItemCode\" = T8.\"ItemCode\"
LEFT JOIN \"ELITE_NUTRITION\".\"OITB\" T9 ON T8.\"ItmsGrpCod\" = T9.\"ItmsGrpCod\"
INNER JOIN \"ELITE_NUTRITION\".\"OPLN\" T10 ON T2.\"ListNum\" = T10.\"ListNum\"
LEFT JOIN \"ELITE_NUTRITION\".\"@ENG_VENDEDORES\" T11 ON T3.\"U_ENG_BraOffSeller\" = T11.\"Code\"
INNER JOIN \"ELITE_NUTRITION\".\"OCRG\" T12 ON T2.\"GroupCode\" = T12.\"GroupCode\"
 WHERE  T1.\"DocDate\" >= '$ini' AND T1.\"DocDate\" <='$fin' AND T1.\"CANCELED\" = 'N'  AND  T7.\"ItemCode\" = '$producto'  
UNION
SELECT DISTINCT T0.\"SeriesName\", T1.\"DocDate\", T1.\"isIns\",T1.\"DocNum\", T7.\"BaseRef\", T2.\"U_ENG_Agrupado\" AS \"Agrupado por\", T1.\"CardCode\", T2.\"CardName\",T1.\"Address2\", T2.\"E_Mail\", T2.\"City\",T10.\"ListName\", T2.\"Phone1\", T11.\"Name\" AS \"Analista Comercial\",T5.\"U_NAME\",  T7.\"LineTotal\" *-1 AS \"TOTAL\", T6.\"Descript\", T7.\"LineNum\", T7.\"ItemCode\", T7.\"Dscription\", T7.\"Quantity\"*-1 AS \"Cantidad\", T7.\"OpenCreQty\", T9.\"ItmsGrpNam\" AS \"Categoria\", T7.\"WhsCode\", T7.\"DiscPrcnt\", T1.\"DiscPrcnt\", T2.\"CreateDate\",T1.\"U_ENG_Medio_Pago\" AS \"Medio de Pgago\", T12.\"GroupName\" AS \"Tipologia\", T1.\"BaseAmnt\", T1.\"VatSum\", T1.\"TotalExpns\", T1.\"WTSum\", T1.\"U_SECUENCIA\" , T7.\"TaxCode\", T1.\"U_ESTADO_AUTORIZACIO\", T2.\"CreditLine\",  T1.\"DocDueDate\", T7.\"GrossBuyPr\"
FROM \"ELITE_NUTRITION\".\"NNM1\"  T0
LEFT JOIN \"ELITE_NUTRITION\".\"ORIN\"  T1 ON T0.\"Series\" = T1.\"Series\"
LEFT  JOIN \"ELITE_NUTRITION\".\"OCRD\"  T2 ON T1.\"CardCode\" = T2.\"CardCode\"
LEFT JOIN \"ELITE_NUTRITION\".\"CRD1\" T3 ON T2.\"CardCode\" = T3.\"CardCode\" AND T1.\"ShipToCode\" = T3.\"Address\"
LEFT JOIN \"ELITE_NUTRITION\".\"OSLP\" T4 ON T1.\"SlpCode\" = T4.\"SlpCode\"
LEFT  JOIN \"ELITE_NUTRITION\".\"OUSR\" T5 ON T1.\"UserSign\" = T5.\"USERID\"
LEFT JOIN \"ELITE_NUTRITION\".\"OPYM\" T6 ON T1.\"PeyMethod\" = T6.\"PayMethCod\"
LEFT JOIN \"ELITE_NUTRITION\".\"RIN1\" T7 ON T1.\"DocEntry\" = T7.\"DocEntry\"
LEFT JOIN \"ELITE_NUTRITION\".\"OITM\" T8 ON T7.\"ItemCode\" = T8.\"ItemCode\"
LEFT JOIN \"ELITE_NUTRITION\".\"OITB\" T9 ON T8.\"ItmsGrpCod\" = T9.\"ItmsGrpCod\"
INNER JOIN \"ELITE_NUTRITION\".\"OPLN\" T10 ON T2.\"ListNum\" = T10.\"ListNum\"
LEFT JOIN \"ELITE_NUTRITION\".\"@ENG_VENDEDORES\" T11 ON T3.\"U_ENG_BraOffSeller\" = T11.\"Code\"
INNER JOIN \"ELITE_NUTRITION\".\"OCRG\" T12 ON T2.\"GroupCode\" = T12.\"GroupCode\"
 WHERE  T1.\"DocDate\" >='$ini' AND T1.\"DocDate\" <= '$fin' AND T1.\"CANCELED\" = 'N'  AND  T7.\"ItemCode\" = '$producto'     ORDER BY T0.\"SeriesName\", T1.\"DocNum\"
";



} 





}


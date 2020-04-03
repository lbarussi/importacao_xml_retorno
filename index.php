<?php

include 'DB.php';

class Run {
    private function importaCabecaNota($cabecaNota){
        try{
            //$cabecaNota = $cabecaNota;
            $cUF = $cabecaNota->cUF[0];
            $cNF =$cabecaNota->cNF[0];
            $natOp =$cabecaNota->natOp[0];
            $mode =$cabecaNota->mod[0];
            $serie =$cabecaNota->serie[0];
            $nNF =$cabecaNota->nNF[0];
            $dhEmi = preg_replace("/[A-Z.]/", " ", $cabecaNota->dhEmi[0]);
            $dhSaiEnt = preg_replace("/[A-Z.]/", " ", $cabecaNota->dhSaiEnt[0]);
            $tpNF =$cabecaNota->tpNF[0];
            $idDest =$cabecaNota->idDest[0];
            $cMunFG =$cabecaNota->cMunFG[0];
            $tpImp =$cabecaNota->tpImp[0];
            $tpEmis =$cabecaNota->tpEmis[0];
            $cDV =$cabecaNota->cDV[0];
            $tpAmb =$cabecaNota->tpAmb[0];
            $finNFe =$cabecaNota->finNFe[0];
            $indFinal =$cabecaNota->indFinal[0];
            $indPres =$cabecaNota->indPres[0];
            $procEmi =$cabecaNota->procEmi[0];
            $verProc =$cabecaNota->verProc[0];

            $divHorarioEm = explode(' ', $dhEmi);
            $dataEmissao = $divHorarioEm[0];
            $horaEmissao =  explode('-', $divHorarioEm[1]);
            $horaEmissao=$horaEmissao[0];
            $dataEmissao = $dataEmissao.' '.$horaEmissao;

            $divHorarioSaida = explode(' ', $dhSaiEnt);
            $dataSaida = $divHorarioSaida[0];
            $horaSaida =  explode('-', $divHorarioSaida[1]);
            $horaSaida=$horaSaida[0];
            $dataSaida = $dataSaida.' '.$horaSaida;

            $dataEmissao = \DateTime::createFromFormat('Y-m-d H:i:s', $dataEmissao);
            $dataSaida = \DateTime::createFromFormat('Y-m-d H:i:s', $dataSaida);

            $seq = DB::nextIncrement('xml_importacao');

            $sql="insert into xml_importacao 
                        (cUF, cNF, natOp, 
                         mode, serie, nNF, 
                         dhEmi, dhSaiEnt, tpNF, 
                         idDest, cMunFG, tpImp, 
                         tpEmis, cDV, tpAmb, 
                         finNFe, indFinal, indPres, 
                         procEmi, verProc) 
                  values (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
            $params=array($cUF, $cNF, $natOp,
                $mode, $serie, $nNF,
                $dataEmissao->format('Y-m-d H:i:s'), $dataSaida->format('Y-m-d H:i:s'), $tpNF,
                $idDest, $cMunFG, $tpImp,
                $tpEmis, $cDV, $tpAmb,
                $finNFe, $indFinal, $indPres,
                $procEmi, $verProc);

            DB::insertWithQuestion($sql, $params);

            return $seq;
        }catch (\Exception $ex){
            echo $ex->getMessage();
            exit;
        }
    }

    private function importaEmitenteNota($codCabeca, $emitente){
        try {
            $cnpj = $emitente->CNPJ[0];
            $xNome = $emitente->xNome[0];
            $xFant = $emitente->xFant[0];
            $xLgr = null;
            $nro = null;
            $xBairro = null;
            $cMun = null;
            $xMun = null;
            $UF = null;
            $CEP = null;
            $cPais = null;
            $xPais = null;
            $fone = null;
            $IE = $emitente->IE[0];
            $CRT = $emitente->CRT[0];
            if(count($emitente[0]->enderEmit) > 0){
                $xLgr = $emitente[0]->enderEmit[0]->xLgr;
                $nro = $emitente[0]->enderEmit[0]->nro;
                $xBairro = $emitente[0]->enderEmit[0]->xBairro;
                $cMun = $emitente[0]->enderEmit[0]->cMun;
                $xMun = $emitente[0]->enderEmit[0]->xMun;
                $UF = $emitente[0]->enderEmit[0]->UF;
                $CEP = $emitente[0]->enderEmit[0]->CEP;
                $cPais = $emitente[0]->enderEmit[0]->cPais;
                $xPais = $emitente[0]->enderEmit[0]->xPais;
                $fone = $emitente[0]->enderEmit[0]->fone;
            }

            $sql="insert into xml_importacao_detalhes 
                        (codigo_xml_importacao, tipo, cnpj, 
                         xNome, xFant, xLgr, 
                         nro, xBairro, cMun, 
                         xMun, UF, CEP, 
                         cPais, xPais, fone, 
                         IE, CRT, email, 
                         indIEDest)
                    values (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

            $params=array($codCabeca, 1, $cnpj,
                          $xNome, $xFant,$xLgr,
                          $nro, $xBairro, $cMun,
                          $xMun, $UF, $CEP,
                          $cPais, $xPais, $fone,
                          $IE, $CRT, null,
                          null);

            DB::insertWithQuestion($sql, $params);
        }catch (\Exception $ex){
            throw new \Exception($ex->getMessage());
        }
    }

    private function importaDestinatarioNota($codCabeca, $destinatario){
        try {
            $cnpj = $destinatario->CNPJ[0];
            $xNome = $destinatario->xNome[0];
            $xFant = $destinatario->xFant[0];
            $indIEDest = $destinatario->indIEDest[0];
            $email = $destinatario->email[0];
            $xLgr = null;
            $nro = null;
            $xBairro = null;
            $cMun = null;
            $xMun = null;
            $UF = null;
            $CEP = null;
            $cPais = null;
            $xPais = null;
            $fone = null;
            $IE = $destinatario->IE[0];
            $CRT = $destinatario->CRT[0];
            if(count($destinatario[0]->enderDest) > 0){
                $xLgr = $destinatario[0]->enderDest[0]->xLgr;
                $nro = $destinatario[0]->enderDest[0]->nro;
                $xBairro = $destinatario[0]->enderDest[0]->xBairro;
                $cMun = $destinatario[0]->enderDest[0]->cMun;
                $xMun = $destinatario[0]->enderDest[0]->xMun;
                $UF = $destinatario[0]->enderDest[0]->UF;
                $CEP = $destinatario[0]->enderDest[0]->CEP;
                $cPais = $destinatario[0]->enderDest[0]->cPais;
                $xPais = $destinatario[0]->enderDest[0]->xPais;
                $fone = $destinatario[0]->enderDest[0]->fone;
            }

            $sql="insert into xml_importacao_detalhes 
                        (codigo_xml_importacao, tipo, cnpj, 
                         xNome, xFant, xLgr, 
                         nro, xBairro, cMun, 
                         xMun, UF, CEP, 
                         cPais, xPais, fone, 
                         IE, CRT, email, 
                         indIEDest)
                    values (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

            $params=array($codCabeca, 2, $cnpj,
                $xNome, $xFant,$xLgr,
                $nro, $xBairro, $cMun,
                $xMun, $UF, $CEP,
                $cPais, $xPais, $fone,
                $IE, $CRT, $email,
                $indIEDest);

            DB::insertWithQuestion($sql, $params);
        }catch (\Exception $ex){
            throw new \Exception($ex->getMessage());
        }
    }

    public function importaProdutosNota($codCabeca, $produto){
        try{
            $cProd = $produto->cProd[0];
            $cEAN = $produto->cEAN[0];
            $xProd = $produto->xProd[0];
            $NCM = $produto->NCM[0];
            $CFOP = $produto->CFOP[0];
            $uCom = $produto->uCom[0];
            $qCom = $produto->qCom[0];
            $vUnCom = $produto->vUnCom[0];
            $vProd = $produto->vProd[0];
            $cEANTrib = $produto->cEANTrib[0];
            $uTrib = $produto->uTrib[0];
            $qTrib = $produto->qTrib[0];
            $vUnTrib = $produto->vUnTrib[0];
            $indTot = $produto->indTot[0];
            $seq = DB::nextIncrement('xml_importacao_produtos');

            $sql="insert into xml_importacao_produtos 
                            (codigo_xml_importacao, cProd, cEAN, 
                             xProd, NCM, CFOP, 
                             uCom, qCom, vUnCom, 
                             vProd, cEANTrib, uTrib, 
                             qTrib, vUnTrib, indTot)
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

            $params=array($codCabeca, $cProd, $cEAN,
                          $xProd, $NCM, $CFOP,
                          $uCom, $qCom, $vUnCom,
                          $vProd, $cEANTrib, $uTrib,
                          $qTrib, $vUnTrib, $indTot);

            DB::insertWithQuestion($sql, $params);
            return $seq;
        }catch (\Exception $ex){
            throw new \Exception($ex->getMessage());
        }
    }

    public function importaImpostoICMS($codProduto, $icms){
        try{
            $orig = $icms->orig[0];
            $CSOSN = $icms->CSOSN[0];
            $vBCSTRet = $icms->vBCSTRet[0];
            $pST = $icms->pST[0];
            $vICMSSubstituto = $icms->vICMSSubstituto[0];
            $vICMSSTRet = $icms->vICMSSTRet[0];

            $sql="insert into xml_importacao_prod_icms 
                                (codigo_xml_imp_prod, orig, CSOSN, 
                                 vBCSTRet, pST, vICMSSubstituto, 
                                 vICMSSTRet)
                        VALUES (?, ?, ?, ?, ?, ?, ?)";

            $params=array($codProduto, $orig, $CSOSN,
                $vBCSTRet, $pST, $vICMSSubstituto,
                $vICMSSTRet);

            DB::insertWithQuestion($sql, $params);
        }catch (\Exception $ex){
            throw new \Exception($ex->getMessage());
        }
    }

    public function importaImpostoIPI($codProduto, $ipi){
        try {
            $cEnq= $ipi->cEnq[0];
            $CST=null;
            $qUnid=null;
            $vUnid=null;
            $vIPI=null;
            if(count($ipi->IPITrib) > 0){
                $CST=$ipi->IPITrib->CST[0];
                $qUnid=$ipi->IPITrib->qUnid[0];
                $vUnid=$ipi->IPITrib->vUnid[0];
                $vIPI=$ipi->IPITrib->vIPI[0];
            }

            $sql="insert into xml_importacao_prod_ipi (codigo_xml_imp_prod, cEnq, CST, qUnid, vUnid, vIPI)
                    VALUES (?, ?, ?, ?, ?, ?)";

            $params=array($codProduto, $cEnq, $CST, $qUnid, $vUnid, $vIPI);
            DB::insertWithQuestion($sql, $params);
        }catch (\Exception $ex){
            throw new \Exception($ex->getMessage());
        }
    }

    public function importaImpostoPIS($codProduto, $pis){
        try {
            $CST = $pis->CST[0];
            $qBCProd = $pis->qBCProd[0];
            $vAliqProd = $pis->vAliqProd[0];
            $vPIS = $pis->vPIS[0];

            $sql="insert into xml_importacao_prod_pis (codigo_xml_imp_prod, qBCProd, vAliqProd, vPIS, cst)
                    VALUES (?, ?, ?, ?, ?)";
            $params = array($codProduto, $qBCProd, $vAliqProd, $vPIS, $CST);

            DB::insertWithQuestion($sql, $params);
        }catch (\Exception $ex){
            throw new \Exception($ex->getMessage());
        }
    }

    public function importaImpostoCofins($codProduto, $cofins){
        try {
            $CST = $cofins->CST[0];
            $qBCProd = $cofins->qBCProd[0];
            $vAliqProd = $cofins->vAliqProd[0];
            $vCOFINS = $cofins->vCOFINS[0];

            $sql="insert into xml_importacao_prod_cofins (codigo_xml_imp_prod, CST, qBCProd, vAliqProd, vCOFINS)
                VALUES (?, ?, ?, ?, ?)";

            $params=array($codProduto, $CST, $qBCProd, $vAliqProd, $vCOFINS);

            DB::insertWithQuestion($sql, $params);
        }catch (\Exception $ex){
            throw new \Exception($ex->getMessage());
        }
    }

    public function importaTotais($codCabeca, $totais, $tipo){
        try {
            $vBC = $totais->vBC[0];
            $vICMS = $totais->vICMS[0];
            $vICMSDeson = $totais->vICMSDeson[0];
            $vFCP = $totais->vFCP[0];
            $vBCST = $totais->vBCST[0];
            $vST = $totais->vST[0];
            $vFCPST = $totais->vFCPST[0];
            $vFCPSTRet = $totais->vFCPSTRet[0];
            $vProd = $totais->vProd[0];
            $vFrete = $totais->vFrete[0];
            $vSeg = $totais->vSeg[0];
            $vDesc = $totais->vDesc[0];
            $vII = $totais->vII[0];
            $vIPI = $totais->vIPI[0];
            $vIPIDevol = $totais->vIPIDevol[0];
            $vPIS = $totais->vPIS[0];
            $vCOFINS = $totais->vCOFINS[0];
            $vOutro = $totais->vOutro[0];
            $vNF = $totais->vNF[0];

            $sql="insert into xml_importacao_totais (codigo_xml_importacao, tipo, vBC, vICMS, vICMSDeson, vFCP, vBCST, vST, vFCPST,
                                   vFCPSTRet, vProd, vFrete, vSeg, vDesc, vII, vIPI, vIPIDevol, vPIS, vCOFINS, vOutro,
                                   vNF)
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $params=array($codCabeca, $tipo, $vBC, $vICMS, $vICMSDeson, $vFCP, $vBCST, $vST, $vFCPST,
                $vFCPSTRet, $vProd, $vFrete, $vSeg, $vDesc, $vII, $vIPI, $vIPIDevol, $vPIS, $vCOFINS,$vOutro,
                $vNF);

            DB::insertWithQuestion($sql, $params);

        }catch (\Exception $ex){
            throw new \Exception($ex->getMessage());
        }
    }


    public function getXml($xml){
        try {
            if(!file_exists($xml)){
                throw new \Exception('Arquivo não encontrado!');
            }

            $xmlLoaded = simplexml_load_file($xml);

            DB::setTransaction();
            $seq = $this->importaCabecaNota($xmlLoaded->NFe[0]->infNFe->ide);
            $this->importaEmitenteNota($seq[0]['id'], $xmlLoaded->NFe[0]->infNFe->emit);
            $this->importaDestinatarioNota($seq[0]['id'], $xmlLoaded->NFe[0]->infNFe->dest);
            foreach($xmlLoaded->NFe[0]->infNFe->det as $prod){
                $codProd = $this->importaProdutosNota($seq[0]['id'], $prod->prod);
                $this->importaImpostoICMS($codProd[0]['id'], $prod->imposto->ICMS->ICMSSN500);
                $this->importaImpostoIPI($codProd[0]['id'], $prod->imposto->IPI);
                $this->importaImpostoPIS($codProd[0]['id'], $prod->imposto->PIS->PISOutr);
                $this->importaImpostoCofins($codProd[0]['id'], $prod->imposto->COFINS->COFINSOutr);
            }

            foreach($xmlLoaded->NFe[0]->infNFe->total as $total){
                foreach($total as $key => $value){
                    $tipo = $key == 'ICMSTot' ? 1 : 2;
                    $this->importaTotais($seq[0]['id'], $value, $tipo);
                }
            }

            DB::commit();
            echo 'XML importada com suceso!';
        }catch (\Exception $ex){
            DB::rollback();
            echo ($ex->getMessage());
        }
    }
}

$class = new Run();
$class->getXml('xml.xml');
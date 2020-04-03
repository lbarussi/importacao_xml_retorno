create table xml_importacao
(
    codigo   int auto_increment
        primary key,
    cUF      int(10)      null comment 'tag <cUF>',
    cNF      varchar(100) null comment 'tag <cNF>',
    natOp    varchar(300) null comment 'tag <natOp>',
    mode     varchar(10)  null comment 'tag <mod> essa tag esta diferente porque o mysql não aceita a palavra reservada MOD',
    serie    varchar(10)  null comment 'tag <serie>',
    nNF      varchar(50)  null comment 'tag <nNF>',
    dhEmi    datetime     null comment 'tag <dhEmi>',
    dhSaiEnt datetime     null comment 'tag <dhSaiEnt>',
    tpNF     varchar(10)  null comment 'tag <tpNF>',
    idDest   varchar(10)  null comment 'tag <idDest>',
    cMunFG   varchar(50)  null comment 'tag <cMunFG>',
    tpImp    varchar(10)  null comment 'tag <tpImp>',
    tpEmis   varchar(10)  null comment 'tag <tpEmis>',
    cDV      varchar(10)  null comment 'tag <cDV>',
    tpAmb    varchar(10)  null comment 'tag <tpAmb>',
    finNFe   varchar(10)  null comment 'tag <finNFe>',
    indFinal varchar(10)  null comment 'tag <indFinal>',
    indPres  varchar(10)  null comment 'tag <indPres>',
    procEmi  varchar(10)  null comment 'tag <procEmi>',
    verProc  varchar(50)  null comment 'tag <verProc>'
)
    comment 'tabela referente a cabeça da nota. Todas as colunas criadas são do mesmo nome das tags da nota. Essa tabela referecia todos os itens da tag <ide> da nota';

create table xml_importacao_detalhes
(
    codigo                int auto_increment
        primary key,
    codigo_xml_importacao int          not null comment 'join table xml_importacao',
    tipo                  int(1)       not null comment '1=<emit> / 2=<dest>',
    cnpj                  varchar(14)  null comment 'referencia a tag <CNPJ>',
    xNome                 varchar(200) null comment 'referencia a tag <xNome>',
    xFant                 varchar(200) null comment 'referencia a tag <xFant>',
    xLgr                  varchar(400) null comment 'referencia a tag <xLgr>',
    nro                   varchar(20)  null comment 'referencia a tag <nro>',
    xBairro               varchar(400) null comment 'referencia a tag <xBairro>',
    cMun                  varchar(40)  null comment 'referencia a tag <cMun>',
    xMun                  varchar(400) null comment 'referencia a tag <xMun>',
    UF                    varchar(10)  null comment 'referencia a tag <UF>',
    CEP                   varchar(10)  null comment 'referencia a tag <CEP>',
    cPais                 varchar(10)  null comment 'referencia a tag <cPais>',
    xPais                 varchar(400) null comment 'referencia a tag <xPais>',
    fone                  varchar(20)  null comment 'referencia a tag <fone>',
    IE                    varchar(50)  null comment 'referencia a tag <IE>',
    CRT                   varchar(10)  null comment 'referencia a tag <CRT>',
    email                 varchar(200) null comment 'referencia a tag <email>',
    indIEDest             varchar(10)  null comment 'referencia a tag <indIEDest>',
    constraint xml_importacao_detalhes_ibfk_1
        foreign key (codigo_xml_importacao) references xml_importacao (codigo)
)
    comment 'tabela referente as tags <emit> e <dest> (emitente e destinatario) ambos diferenciados por um tipo';

create index codigo_xml_importacao
    on xml_importacao_detalhes (codigo_xml_importacao);

create table xml_importacao_produtos
(
    codigo                int auto_increment
        primary key,
    codigo_xml_importacao int           not null comment 'join table xml_importacao',
    cProd                 varchar(50)   null comment 'referencia a tag <cProd>',
    cEAN                  varchar(50)   null comment 'referencia a tag <cEAN>',
    xProd                 varchar(400)  null comment 'referencia a tag <xProd>',
    NCM                   varchar(100)  null comment 'referencia a tag <NCM>',
    CFOP                  varchar(100)  null comment 'referencia a tag <CFOP>',
    uCom                  varchar(100)  null comment 'referencia a tag <uCom>',
    qCom                  int(10)       null comment 'referencia a tag <qCom>',
    vUnCom                decimal(9, 2) null comment 'referencia a tag <vUnCom>',
    vProd                 decimal(9, 2) null comment 'referencia a tag <vProd>',
    cEANTrib              varchar(100)  null comment 'referencia a tag <cEANTrib>',
    uTrib                 varchar(100)  null comment 'referencia a tag <uTrib>',
    qTrib                 varchar(100)  null comment 'referencia a tag <qTrib>',
    vUnTrib               decimal(9, 2) null comment 'referencia a tag <vUnTrib>',
    indTot                int(10)       null comment 'referencia a tag <indTot>',
    constraint xml_importacao_produtos_ibfk_1
        foreign key (codigo_xml_importacao) references xml_importacao (codigo)
)
    comment 'referencia tdas as tags referente a tag <det> (detalhes do produto)';

create table xml_importacao_prod_cofins
(
    codigo              int auto_increment
        primary key,
    codigo_xml_imp_prod int           not null comment 'join table xml_importacao_produtos',
    CST                 int(10)       null comment 'referencia a tag <CST>',
    qBCProd             decimal(9, 7) null comment 'referencia a tag <qBCProd>',
    vAliqProd           decimal(9, 7) null comment 'referencia a tag <vAliqProd>',
    vCOFINS             int(10)       null comment 'referencia a tag <vCOFINS>',
    constraint xml_importacao_prod_cofins_ibfk_1
        foreign key (codigo_xml_imp_prod) references xml_importacao_produtos (codigo)
)
    comment 'referencia a tag <cofins> dentro da tag <imposto> de cada produto ';

create index codigo_xml_imp_prod
    on xml_importacao_prod_cofins (codigo_xml_imp_prod);

create table xml_importacao_prod_icms
(
    codigo              int auto_increment
        primary key,
    codigo_xml_imp_prod int           not null comment 'join table xml_importacao_produtos',
    orig                int(10)       null comment 'referencia a tag <orig>',
    CSOSN               int(10)       null comment 'referencia a tag <CSOSN>',
    vBCSTRet            decimal(9, 2) null comment 'referencia a tag <vBCSTRet>',
    pST                 decimal(9, 2) null comment 'referencia a tag <pST>',
    vICMSSubstituto     decimal(9, 2) null comment 'referencia a tag <vICMSSubstituto>',
    vICMSSTRet          decimal(9, 2) null comment 'referencia a tag <vICMSSTRet>',
    constraint xml_importacao_prod_icms_ibfk_1
        foreign key (codigo_xml_imp_prod) references xml_importacao_produtos (codigo)
)
    comment 'referencia a tag <icms> dentro da tag <imposto> de cada produto ';

create index codigo_xml_imp_prod
    on xml_importacao_prod_icms (codigo_xml_imp_prod);

create table xml_importacao_prod_ipi
(
    codigo              int auto_increment
        primary key,
    codigo_xml_imp_prod int           not null comment 'join table xml_importacao_produtos',
    cEnq                int(10)       null comment 'referencia a tag <cEnq>',
    CST                 int(10)       null comment 'referencia a tag <CST>',
    qUnid               decimal(9, 7) null comment 'referencia a tag <qUnid>',
    vUnid               decimal(9, 7) null comment 'referencia a tag <qUnid>',
    vIPI                decimal(9, 2) null comment 'referencia a tag <vIPI>',
    constraint xml_importacao_prod_ipi_ibfk_1
        foreign key (codigo_xml_imp_prod) references xml_importacao_produtos (codigo)
)
    comment 'referencia a tag <ipi> dentro da tag <imposto> de cada produto ';

create index codigo_xml_imp_prod
    on xml_importacao_prod_ipi (codigo_xml_imp_prod);

create table xml_importacao_prod_pis
(
    codigo              int auto_increment
        primary key,
    codigo_xml_imp_prod int           not null comment 'join table xml_importacao_produtos',
    qBCProd             decimal(9, 7) null comment 'referencia a tag <qBCProd>',
    vAliqProd           decimal(9, 7) null comment 'referencia a tag <vAliqProd>',
    vPIS                int(10)       null comment 'referencia a tag <vPIS>',
    cst                 int(10)       null comment 'referencia a tag <CST>',
    constraint xml_importacao_prod_pis_ibfk_1
        foreign key (codigo_xml_imp_prod) references xml_importacao_produtos (codigo)
)
    comment 'referencia a tag <pis> dentro da tag <imposto> de cada produto ';

create index codigo_xml_imp_prod
    on xml_importacao_prod_pis (codigo_xml_imp_prod);

create index codigo_xml_importacao
    on xml_importacao_produtos (codigo_xml_importacao);

create table xml_importacao_totais
(
    codigo                int auto_increment
        primary key,
    codigo_xml_importacao int           not null comment 'join table xml_importacao',
    tipo                  int(1)        null comment '1 - <ICMSTot>',
    vBC                   decimal(9, 2) null comment 'referencia a tag <vBC>',
    vICMS                 decimal(9, 2) null comment 'referencia a tag <vICMS>',
    vICMSDeson            decimal(9, 2) null comment 'referencia a tag <vICMSDeson>',
    vFCP                  decimal(9, 2) null comment 'referencia a tag <vFCP>',
    vBCST                 decimal(9, 2) null comment 'referencia a tag <vBCST>',
    vST                   decimal(9, 2) null comment 'referencia a tag <vST>',
    vFCPST                decimal(9, 2) null comment 'referencia a tag <vFCPST>',
    vFCPSTRet             decimal(9, 2) null comment 'referencia a tag <vFCPSTRet>',
    vProd                 decimal(9, 2) null comment 'referencia a tag <vProd>',
    vFrete                decimal(9, 2) null comment 'referencia a tag <vFrete>',
    vSeg                  decimal(9, 2) null comment 'referencia a tag <vSeg>',
    vDesc                 decimal(9, 2) null comment 'referencia a tag <vDesc>',
    vII                   decimal(9, 2) null comment 'referencia a tag <vII>',
    vIPI                  decimal(9, 2) null comment 'referencia a tag <vIPI>',
    vIPIDevol             decimal(9, 2) null comment 'referencia a tag <vIPIDevol>',
    vPIS                  decimal(9, 2) null comment 'referencia a tag <vPIS>',
    vCOFINS               decimal(9, 2) null comment 'referencia a tag <vCOFINS>',
    vOutro                decimal(9, 2) null comment 'referencia a tag <vOutro>',
    vNF                   decimal(9, 2) null comment 'referencia a tag <vNF>',
    constraint xml_importacao_totais_ibfk_1
        foreign key (codigo_xml_importacao) references xml_importacao (codigo)
)
    comment 'referencia a tag <total>'

create index codigo_xml_importacao
    on xml_importacao_totais (codigo_xml_importacao);


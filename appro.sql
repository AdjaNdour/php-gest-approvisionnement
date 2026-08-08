
CREATE TABLE fournisseurs (
    id SERIAL PRIMARY KEY,
    nomEntrprise VARCHAR(30) NOT NULL,
    telephone VARCHAR(30) UNIQUE NOT NULL,
    adresse VARCHAR(30) UNIQUE
);

INSERT INTO fournisseurs (nomEntrprise,telephone,adresse) 
VALUES
('compagny css','771001010','diamalaye'),
('Diop et frères','771234537','yoff'),
('Ndour et Soeur','771234567','ville'),
('wane family','771234561','foire');


CREATE TABLE etatAppro(
    id SERIAL PRIMARY KEY,
    nomEtat VARCHAR(30) UNIQUE NOT NULL
);

INSERT INTO etatAppro (nomEtat) 
VALUES
('En Cours'),
('Receptionné');

CREATE TABLE articles(
    id SERIAL PRIMARY KEY,
    libelle VARCHAR(30) NOT NULL, 
    prixAchat NUMERIC(10,2),
    qteStock INTEGER,
    fournisseur_id INT REFERENCES fournisseurs(id)
);

INSERT INTO articles (libelle,prixAchat,qteStock,fournisseur_id)
VALUES 
('carton de savon',1600,20,1),
('sac de riz',25000,10,1),
('boite de lait',20000,40,1),
('bobine file',12500,1100,1),
('sac de sucre',250000,30,2),
('sac de sel',25000,40,3),
('bonbon sur nous ',2000,500,4);

INSERT INTO articles (libelle,prixAchat,qteStock,fournisseur_id)
VALUES 
('voile de femme',1600,2,1),
('twins',1600,3,1),
('ndiappe',1600,5,1);

CREATE TABLE approvisionnements(
    id SERIAL PRIMARY KEY,
    dateAppro DATE DEFAULT CURRENT_DATE,
    refBL VARCHAR(30) not null,
    etat_id INT REFERENCES etatAppro(id),
    fournisseur_id INT REFERENCES fournisseurs(id)
);
SELECT * FROM approvisionnements;
-- BAXXOUL DELETE COLUMN etatAppro_id FROM approvisionnements;

ALTER TABLE approvisionnements
ALTER COLUMN etatAppro_id SET NOT NULL;

UPDATE approvisionnements SET etatappro_id = 1;

ALTER TABLE approvisionnements
ADD COLUMN etatAppro_id INTEGER REFERENCES etatAppro(id);
CREATE TABLE ligneAppro(
    id SERIAL PRIMARY KEY,
    quantiteAppro INTEGER,
    quantiteRecu INTEGER,
    prixAppro NUMERIC(10,2),
    appro_id INT REFERENCES approvisionnements(id),
    article_id INT REFERENCES articles(id)
);

BEGIN;
    WITH saveAppro AS (
        INSERT INTO approvisionnements (refBL, etatAppro, fournisseur_id)
        VALUES ('BL-ODC-NDOUR', 'En Cours', 1)
        RETURNING id
    ),
    saveLigneAppro AS (
        INSERT INTO ligneAppro (quantiteAppro,quantiteRecu,prixAppro,appro_id,article_id)
        VALUES
            (2, 1, 250000, (SELECT id FROM saveAppro), 5)
    )
    UPDATE articles SET qteStock = qteStock - 2 WHERE id = 5;

COMMIT;
ROLLBACK;
TRUNCATE approvisionnements RESTART IDENTITY CASCADE;

SELECT * FROM approvisionnements;
SELECT * FROM ligneAppro;
SELECT * FROM articles;


-- recuperer les approvisionnement avec le fournisseur laa valeurreceptionne et le valeurfacturedemande

SELECT (la.quantiteRecu *la.prixAppro) as valeurFacture,(la.quantiteAppro *la.prixAppro) as valeurReceptionne
    FROM ligneAppro la INNER JOIN approvisionnements a ON la.appro_id = a.id ;


SELECT a.id, a.refBL, f.nomentrprise, a.etatAppro, 
    SUM(la.quantiteRecu *la.prixAppro) as valeurFacture,
    SUM(la.quantiteAppro *la.prixAppro) as valeurReceptionne,
    CASE 
        WHEN  SUM(la.quantiteAppro *la.prixAppro) > SUM(la.quantiteRecu *la.prixAppro) THEN 'ECART-'||SUM(la.quantiteAppro *la.prixAppro) - SUM(la.quantiteRecu *la.prixAppro)
        ELSE 'Concorde'
    END as typee ,
    CASE 
        WHEN SUM(la.quantiteAppro * la.prixAppro) > 
             SUM(la.quantiteRecu * la.prixAppro)
        THEN 'yellow'
        ELSE 'green'
    END AS couleur
    FROM approvisionnements a INNER JOIN fournisseurs f
    ON a.fournisseur_id = f.id INNER JOIN ligneAppro la ON la.appro_id = a.id 
    GROUP BY a.id, a.refBL, f.nomentrprise, a.etatAppro;



SELECT a.id , a.libelle, a.qteStock,f.nomEntrprise ,
        CASE WHEN a.qteStock > 2 THEN 'warning'
        ELSE 'danger' END AS couleur
        FROM articles a INNER JOIN fournisseurs f ON a.fournisseur_id = f.id  WHERE  a.qteStock <= seuil ORDER BY a.qteStock DESC;


ALTER TABLE articles 
ADD COLUMN seuil int;

UPDATE articles
SET seuil = 5;


SELECT a.id,a.dateappro, a.refBL, f.nomentrprise, a.etatAppro_id,
    SUM(la.quantiteRecu *la.prixAppro) as valeurFacture,
    SUM(la.quantiteAppro *la.prixAppro) as valeurReceptionne,
    a.etatappro_id , ea.nomEtat,
    CASE 
        WHEN SUM(la.quantiteAppro * la.prixAppro) > SUM(la.quantiteRecu * la.prixAppro) THEN 'danger'
        ELSE 'success'
    END AS couleur
    FROM approvisionnements a INNER JOIN fournisseurs f
    ON a.fournisseur_id = f.id INNER JOIN ligneAppro la ON la.appro_id = a.id
    INNER JOIN etatAppro ea ON ea.id = a.etatappro_id
    GROUP BY a.id,a.dateappro, a.refBL, f.nomentrprise, a.etatAppro_id,ea.nomEtat;

SELECT ar.libelle, la.quantiteRecu , la.prixAppro 
FROM ligneAppro la INNER JOIN articles ar ON la.article_id = ar.id 
INNER JOIN approvisionnements a ON la.appro_id = a.id 
WHERE  a.id = 7  ;

SELECT * FROM approvisionnements;

SELECT a.id,a.dateappro, a.refBL, f.nomentrprise, a.etatAppro_id,
    SUM(la.quantiteRecu *la.prixAppro) as valeurFacture,
    SUM(la.quantiteAppro *la.prixAppro) as prixDemande,
    a.etatappro_id , ea.nomEtat
    FROM approvisionnements a INNER JOIN fournisseurs f
    ON a.fournisseur_id = f.id INNER JOIN ligneAppro la ON la.appro_id = a.id
    INNER JOIN etatAppro ea ON ea.id = a.etatappro_id
    GROUP BY a.id,a.dateappro, a.refBL, f.nomentrprise, a.etatAppro_id, ea.nomEtat;

    SELECT ar.libelle, la.quantiteRecu , la.prixAppro, la.quantiteAppro
            FROM ligneAppro la INNER JOIN articles ar ON la.article_id = ar.id
            INNER JOIN approvisionnements a ON la.appro_id = a.id
            WHERE  a.id = 5;



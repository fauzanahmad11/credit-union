SELECT * FROM petugas WHERE idpetugas='5';

INSERT INTO anggota VALUES('07201502222','fauzan ahmad','jalan','085556555','wirausaha','aktif',NOW());
SELECT MAX(SUBSTRING(nokta, -5)) AS noKta FROM anggota;
SELECT*FROM anggota WHERE nokta='03202002223';
SELECT*FROM `riwayatanggota`;
SELECT*FROM `riwayattransaksi`;
SELECT MAX(SUBSTRING(nokta, -5)) AS nokta FROM anggota
SELECT*FROM `sianggota`;
SELECT*FROM `simapan`;
SELECT*FROM `sisukarela`;
SELECT*FROM `siwapo`;
SELECT*FROM `pinjaman`;
SELECT*FROM `angsuran`;
SELECT*FROM `masterbunga`;
SELECT MAX(saldo) AS saldomax,`view-sisukarela`.* FROM `view-sisukarela` WHERE `status`='aktif' GROUP BY `nokta`;
SELECT SUM(nilai) AS saldo,`view-simapan`.* FROM `view-simapan` WHERE `status`='aktif' GROUP BY `nokta`;

SELECT*FROM `view-simapan` WHERE notransaksi='12062000001'
SELECT*FROM `view-transaksi` WHERE notransaksi='12062000001'
SELECT*FROM login
ALTER TABLE masterbunga AUTO_INCREMENT = 1

INSERT INTO siwapo VALUES ('','btop002','04202002226','14','6','debit sipokok','100000','100000','aktif',NOW());
INSERT INTO siwapo VALUES ('','btop002','04202002226','13','6','debit siwajib','100000','100000','aktif',NOW());
INSERT INTO siwapo VALUES ('','btop002','04202002225','14','6','kredit sipokok','-20000','50000','aktif',NOW());

INSERT INTO `sisukarela` VALUES ('btop001','03202002223','7','100000','0','100000','aktif',NOW());
INSERT INTO `sisukarela` VALUES ('btop002','03202002223','7','0','20000','80000','aktif',NOW());

INSERT INTO `sianggota` VALUES ('','btop003','04202002226','17','6','5',NOW(),'02-06-0000','2000000','1','20000','aktif',NOW());

SELECT SUM(subtotal) FROM siwapo WHERE nokta='03202002223' AND keterangan LIKE '%siwajib%'
SELECT SUM(subtotal) FROM siwapo WHERE nokta='03202002223' AND keterangan LIKE '%sipokok%'
SELECT*FROM `riwayattransaksi` WHERE notransaksi='btop002'

SELECT * FROM anggota










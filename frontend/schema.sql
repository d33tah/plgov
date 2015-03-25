DROP TABLE IF EXISTS plgov_entries;


CREATE TABLE plgov_entries (
  entry_id INT NOT NULL AUTO_INCREMENT,
  title TEXT NOT NULL,
  ip TEXT NOT NULL,
  rdns TEXT,
  timestamp TEXT NOT NULL,
  url text NOT NULL,
  PRIMARY KEY (entry_id)
) ENGINE=MyISAM;

DROP TABLE IF EXISTS plgov_ips;
CREATE TABLE plgov_ips (
  entry_id INT NOT NULL,
  ip VARCHAR(16) NOT NULL,
  CONSTRAINT entry_id_fk
  FOREIGN KEY entry_id_fk(entry_id) REFERENCES plgov_entries (entry_id)
) ENGINE=MyISAM;

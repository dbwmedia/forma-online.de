# dbw childtheme

Status Readme → WIP

## 1. Installation _node_modules_

→ initial / nach lokaler Einrichtung des Projektes

```bash
cd wp-content/themes/mastertheme/
```

```bash
npm install
```

_Weitere Infos s.u. "Hinweise"_

<br>

## 2. build-Prozess starten

→ jedes Mal auszuführen, wenn Projekt geöffnet wird

```bash
cd wp-content/themes/mastertheme/
```

```bash
npm start
```

Der build-Prozess läuft im Hintergrund und buildet aus den Dateien im Ordner **src** automatisch für den Browser lesbare **.js**- und **.css**-Dateien (im Ordner **build**).

**Tipp:** Habt das Terminalfenster im Blick, da der build-Prozess nur solange seinen Job erfüllt, wenn ihr grüne und gelbe Schriftfarben seht. Wenn er Fehler erkennt, stoppt der Prozess, bis das Problem gelöst wurde. Woran der Fehler liegt, ist dann in rot im Terminal beschrieben.

Um die Änderungen zu sehen, muss dann eigentlich nur der build-Ordner auf den FTP gelegt werden. Das ist während ihr daran arbeitet völlig okay, vergesst aber beim Beenden eurer Arbeit nicht alle von euch bearbeitenden Dateien auf den FTP zu schubsen und zu **pushen**!

Wenn ihr mit PhpStorm arbeitet habt ihr es hier leichter - ihr könnt dann nämlich Terminal, FTP und Git direkt daraus steuern und eure lokal bearbeitenden Dateien werden beim Speichern automatisch auf den Server geschoben (mit Revisionskontrolle, daher warnt er euch, wenn ihr bspw. eine neuere Datei überschreiben würdet). Für Projekte mit node_modules kann ich euch PhpStorm wirklich nur ans Herz legen, da ihr das komplette Theme/Repo lokal abliegen haben und bearbeiten müsst.
**Noch einmal der Hinweis: node_modules haben auf dem FTP nichts zu suchen!** PhpStorm schließt diese übrigens automatisch vom Upload aus.

### Achtung!

Aktuell können BE + FE **nicht gleichzeitig** an einer Seite arbeiten, sofern beide im src-Ordner arbeiten. Ihr überschreibt euch sonst gegenseitig die Dateien im build-Ordner.

Dieser liegt übrigens nicht im Git ab, da ansonsten ständig Changes bemeckert werden. Auf dem FTP-Server muss der build-Ordner mit allen Inhalten aber unbedingt abliegen, da er die Dateien im src-Ordner nicht lesen kann.

Wenn einer an php-Dateien arbeitet und der andere an scss, geht gleichzeitige Zusammenarbeit allerdings schon - der Backendler darf dann nur nicht seinen build-Prozess starten.

<br>

## 3. allgemeiner Workflow

Sobald ihr das Projekt im Editorgeöffnet habt, geht bitte folgende Punkte durch.

1. Pullen, aber moch nicht speichern / hochladen **→ ganz wichtig!**
2. Dann den aktuellen Stand aller **repo-relevanten Dateien** vom FTP runterladen (im Normalfall reicht der childtheme-Ordner aus).
   - Für PhpStormers: Wenn eine Meldung aufloppt, ob ihr eure lokalen Dateien wirklich überschreiben wollt, könnte ihr idR auf "Always" klicken.
3. Falls Änderungen zum Repo bestehen. diese gerne comitten bevor ihr los legt,
4. Terminal öffnen, obige Befehle (s. [Punkt 2][2]) ausführen und loslegen.
5. Sobald ein Task erledigt ist, legt alle Änderungen in einen commit zusammen, bevor ihr an den nächsten Task geht.
6. **Nach einem commit immer erst pullen, bevor ihr es pusht!**
7. Jetzt noch alle bearbeiteten Dateien + ggf. neue Dateistände aus einem Pull auf den Server legen und prüfen, ob die Website nicht kaputt ist - fertig!

<br>

---

<br>

### Hinweise

Unter Umständen erscheint nach Abschluss der Installation (s. Punkt 1) im Terminal diese oder eine ähnliche Meldung:
_[Bild folgt noch // TODO FW]_

Die Meldung `1 moderate severity vulnerability` erscheint beispielsweise, wenn es inzwischen neuere Versionen der **node_modules** gibt, als in der **package.json** angegeben. Mit dem Befehl `npm audit fix` werden die veralteten Versionen geupdatet und mögliche Sicherheitslücken behoben.

Die Info `npm notice` `New minor version of npm available` erhaltet ihr, wenn ein Update für **npm** (= Node Package Manager) vorliegt. Auch hier müsst ihr einfach nur den angezeigten Befehl ausführen - im Beispiel oben wäre das <code>npm install -g npm@10.5.0</code>.

**Bitte an alle:** Schaut einfach regelmäßig nach, ob eure Versionen noch aktuell sind. Im Falle des Falles habt ihr ansonsten nämlich eventuell eine veraltetes, unsicheres "Programm" auf euren PCs, über das regelmäßig gedownloadet wird - ich denke, es ist klar, warum hier regelmäßige Updates wichtig sind.

Mit dem Befehl `npm doctor` werden eure **npm**- und **node**-Versionen in einem Rutsch geprüft und schlägt im Falle eines Updates folgende Befehle vor:<br>
_[Bild folgt noch // TODO FW]_# forma-online.de

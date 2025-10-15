# 🧑‍💻 Git Tutorial – Arbeiten als Entwickler mit Git

> **Ziel:** Dieses Tutorial vermittelt dir die wichtigsten Git-Befehle und Workflows, um als Entwickler:in effizient im Team zu arbeiten.

---

## 📘 Inhaltsverzeichnis

- [🧑‍💻 Git Tutorial – Arbeiten als Entwickler mit Git](#-git-tutorial--arbeiten-als-entwickler-mit-git)
  - [📘 Inhaltsverzeichnis](#-inhaltsverzeichnis)
  - [🧭 Was ist Git?](#-was-ist-git)
  - [⚙️ Installation \& Konfiguration](#️-installation--konfiguration)
    - [🔧 Installation](#-installation)
    - [🧾 Erste Konfiguration](#-erste-konfiguration)
  - [📂 Grundlegende Git-Begriffe](#-grundlegende-git-begriffe)
  - [🆕 Ein neues Projekt starten](#-ein-neues-projekt-starten)
  - [🔗 Mit einem bestehenden Repository arbeiten](#-mit-einem-bestehenden-repository-arbeiten)
  - [🧩 Dateien verwalten (add, commit, status)](#-dateien-verwalten-add-commit-status)
  - [🌿 Branches verwenden](#-branches-verwenden)
  - [🤝 Zusammenarbeit mit Remote-Repositories (GitHub, GitLab, etc.)](#-zusammenarbeit-mit-remote-repositories-github-gitlab-etc)
    - [Remote hinzufügen](#remote-hinzufügen)
    - [Hochladen (Push)](#hochladen-push)
    - [Änderungen vom Server holen (Pull)](#änderungen-vom-server-holen-pull)
    - [Neue Branches hochladen](#neue-branches-hochladen)
  - [⚔️ Merge-Konflikte lösen](#️-merge-konflikte-lösen)
  - [🧠 Best Practices](#-best-practices)
  - [🧰 Nützliche Git-Befehle im Überblick](#-nützliche-git-befehle-im-überblick)
  - [🚀 Bonus: Typischer Entwickler-Workflow](#-bonus-typischer-entwickler-workflow)
  - [🧩 Ressourcen](#-ressourcen)

---

## 🧭 Was ist Git?

**Git** ist ein _verteiltes Versionskontrollsystem_.  
Es verfolgt Änderungen im Quellcode und ermöglicht es mehreren Entwicklern, **gleichzeitig an einem Projekt** zu arbeiten, ohne sich gegenseitig in die Quere zu kommen.

**Vorteile:**

- Änderungen nachvollziehen & rückgängig machen
- Zusammenarbeit im Team
- Parallele Entwicklung über _Branches_
- Dezentral (jeder Entwickler hat eine komplette Kopie)

---

## ⚙️ Installation & Konfiguration

### 🔧 Installation

**macOS:**

```bash
brew install git
```

**Linux (Ubuntu/Debian):**

```bash
sudo apt update
sudo apt install git
```

**Windows:**
➡️ [Git für Windows herunterladen](https://git-scm.com/download/win)

---

### 🧾 Erste Konfiguration

Nach der Installation musst du deinen Namen und deine E-Mail-Adresse angeben:

```bash
git config --global user.name "Codie Coder"
git config --global user.email "codie@coders.com"
```

Prüfen:

```bash
git config --list
```

---

## 📂 Grundlegende Git-Begriffe

| Begriff               | Bedeutung                                              |
| --------------------- | ------------------------------------------------------ |
| **Repository (Repo)** | Dein Projektordner, der Git-Informationen enthält      |
| **Commit**            | Eine gespeicherte Änderung am Projekt                  |
| **Branch**            | Eine separate Entwicklungs-„Spur“                      |
| **Merge**             | Das Zusammenführen von Branches                        |
| **Remote**            | Eine entfernte Version deines Repos (z. B. auf GitHub) |
| **HEAD**              | Zeiger auf den aktuellen Commit oder Branch            |

---

## 🆕 Ein neues Projekt starten

```bash
# 1. Neuen Ordner anlegen
mkdir mein-projekt
cd mein-projekt

# 2. Git initialisieren
git init

# 3. Eine Datei erstellen
echo "# Mein Projekt" > README.md

# 4. Datei hinzufügen
git add README.md

# 5. Ersten Commit speichern
git commit -m "Initial commit"
```

---

## 🔗 Mit einem bestehenden Repository arbeiten

```bash
# Repository von GitHub klonen
git clone https://github.com/benutzername/projekt.git

# In das Verzeichnis wechseln
cd projekt

# Prüfen, ob alles geklappt hat
git status
```

---

## 🧩 Dateien verwalten (add, commit, status)

| Aktion                  | Befehl                         | Beschreibung                           |
| ----------------------- | ------------------------------ | -------------------------------------- |
| Status prüfen           | `git status`                   | Zeigt Änderungen im Arbeitsverzeichnis |
| Änderungen vormerken    | `git add <datei>`              | Markiert Datei für den nächsten Commit |
| Alles hinzufügen        | `git add .`                    | Fügt alle Änderungen hinzu             |
| Commit erstellen        | `git commit -m "Beschreibung"` | Speichert Änderung dauerhaft           |
| Commit-Historie ansehen | `git log`                      | Zeigt alle bisherigen Commits          |

---

## 🌿 Branches verwenden

```bash
# Neuen Branch erstellen
git branch feature/login

# Zu Branch wechseln
git checkout feature/login
# (oder kombiniert)
git checkout -b feature/login

# Änderungen committen
git add .
git commit -m "Add login feature"

# Zurück zum Hauptbranch
git checkout main

# Branch zusammenführen
git merge feature/login
```

---

## 🤝 Zusammenarbeit mit Remote-Repositories (GitHub, GitLab, etc.)

### Remote hinzufügen

```bash
git remote add origin https://github.com/benutzername/projekt.git
```

### Hochladen (Push)

```bash
git push -u origin main
```

### Änderungen vom Server holen (Pull)

```bash
git pull origin main
```

### Neue Branches hochladen

```bash
git push origin feature/login
```

---

## ⚔️ Merge-Konflikte lösen

Wenn zwei Personen dieselbe Zeile ändern, entsteht ein Konflikt.

1. Git markiert die Konfliktstellen in der Datei:
   ```text
   <<<<<<< HEAD
   deine Version
   =======
   deren Version
   >>>>>>> feature/login
   ```
2. Bearbeite die Datei manuell.
3. Führe die Änderung zusammen:
   ```bash
   git add <konflikt-datei>
   git commit
   ```

---

## 🧠 Best Practices

✅ **Commit klein und häufig**  
✅ **Aussagekräftige Commit-Messages** (z. B. `Fix login bug on Safari`)  
✅ **Feature-Branches nutzen**  
✅ **Pull regelmäßig ausführen**  
✅ **Vor dem Push testen**  
✅ **Nie direkt in `main` entwickeln**

---

## 🧰 Nützliche Git-Befehle im Überblick

| Zweck                                 | Befehl                            |
| ------------------------------------- | --------------------------------- |
| Repository klonen                     | `git clone <url>`                 |
| Aktuellen Status anzeigen             | `git status`                      |
| Änderungen anzeigen                   | `git diff`                        |
| Commit-Historie anzeigen              | `git log --oneline --graph --all` |
| Branch-Liste                          | `git branch`                      |
| Branch löschen                        | `git branch -d <name>`            |
| Änderungen rückgängig machen          | `git restore <datei>`             |
| Commit rückgängig machen              | `git revert <commit-id>`          |
| Temporär Änderungen speichern         | `git stash`                       |
| Temporäre Änderungen wiederherstellen | `git stash pop`                   |

---

## 🚀 Bonus: Typischer Entwickler-Workflow

```bash
# 1. Neues Feature starten
git checkout -b feature/xyz

# 2. Änderungen umsetzen
# (Code schreiben)

# 3. Änderungen sichern
git add .
git commit -m "Implementiere Feature XYZ"

# 4. Auf GitHub pushen
git push origin feature/xyz

# 5. Pull Request im Web-Interface erstellen

# 6. Nach Review in main mergen
git checkout main
git pull
git merge feature/xyz
git push
```

---

## 🧩 Ressourcen

- 📘 [Git Book (offizielle Dokumentation)](https://git-scm.com/book/de/v2)
- 🧑‍🏫 [GitHub Learning Lab](https://lab.github.com/)
- 🎓 [Atlassian Git Tutorial](https://www.atlassian.com/git/tutorials)

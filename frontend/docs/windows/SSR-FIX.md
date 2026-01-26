# Starhotel (SvelteKit) SSR fix: `cle2test.test` TLS trust voor Node

## Wat was het probleem?

* In de browser werkte `https://cle2test.test/api/rooms` wel.
* Maar bij **Ctrl+R / direct URL** doet SvelteKit **SSR** en dan fetch’t **Node** (undici).
* Node gaf: `UNABLE_TO_VERIFY_LEAF_SIGNATURE` → Node vertrouwt de (Valet) certificaatketen niet.

## Doel

Zorgen dat Node het Valet CA-certificaat vertrouwt via `NODE_EXTRA_CA_CERTS`.

---

## Stap 1 — Maak een certs map (in frontend root)

Open PowerShell in:
`...\CLE-Starhotel\frontend`

```powershell
mkdir certs
```

---

## Stap 2 — Exporteer de Valet CA uit Windows (certmgr)

1. `Win + R` → typ: `certmgr.msc` → Enter
2. Ga naar:
   **Trusted Root Certification Authorities** → **Certificates**
3. Zoek de CA met naam (of iets vergelijkbaars):
   **Laravel Valet CA Self Signed CN**
4. Rechtsklik → **All Tasks** → **Export…**
5. Kies:
   ✅ **Base-64 encoded X.509 (.CER)**
6. Sla op als:
   `...\CLE-Starhotel\frontend\certs\valet-ca.cer`

### Check (belangrijk)

Open `valet-ca.cer` in Notepad.
Het moet beginnen met:
`-----BEGIN CERTIFICATE-----`

---

## Stap 3 — Kopieer/rename naar .pem

```powershell
copy "$PWD\certs\valet-ca.cer" "$PWD\certs\cle2test-ca.pem"
```

---

## Stap 4 — Zet de env var in dezelfde terminal

```powershell
$env:NODE_EXTRA_CA_CERTS = "$PWD\certs\cle2test-ca.pem"
```
```powershell
$env:NODE_EXTRA_CA_CERTS = "$PWD\certs\valet-ca.pem"
```

Controle:

```powershell
echo $env:NODE_EXTRA_CA_CERTS
Test-Path $env:NODE_EXTRA_CA_CERTS
```

`Test-Path` moet `True` geven.

---

## Stap 5 — Test of Node de API kan bereiken

```powershell
node -e "fetch('https://cle2test.test/api/rooms').then(r=>console.log('STATUS',r.status)).catch(e=>{console.error('FAIL'); console.error('CAUSE', e.cause);})"
```

Verwacht:
`STATUS 200`

---

## Stap 6 — Start de dev server in dezelfde terminal

```powershell
npm run dev
```

Nu moet `/test` ook bij **Ctrl+R** blijven werken (geen 500 meer).

---

# Handig voor het team (optioneel)

Omdat iedereen anders steeds die env var moet zetten, kun je dit automatiseren.

## Optie A: per dev sessie (simpel)

Iedereen doet eerst:

```powershell
$env:NODE_EXTRA_CA_CERTS = "$PWD\certs\cle2test-ca.pem"
npm run dev
```

## Optie B: via package.json (met cross-env)

1. Installeer:

```bash
npm i -D cross-env
```

2. Pas script aan (voorbeeld):

```json
"dev": "cross-env NODE_EXTRA_CA_CERTS=certs/cle2test-ca.pem vite dev"
```

Let op: het pad is relatief t.o.v. de frontend root.

---

## Troubleshooting

* Zie je: `Warning: Ignoring extra certs ... ASN1 lib`
  → je `.pem` is niet geldig. Exporteer opnieuw als **Base-64 X.509 (.CER)** en copy naar `.pem` (geen certutil encode gebruiken).
* Werkt klikken wel maar Ctrl+R niet
  → Node trust is nog niet correct gezet in de terminal die `npm run dev` draait.

---

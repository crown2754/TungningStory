# 東寧物語 (Tungning Story)

![東寧物語 Logo](public/images/logo-tungning-bg.png)

> 重返 1662 年的台灣，在明鄭時期的東寧王國中經營商號、探索歷史，並與搭載 AI 記憶的 NPC 互動。

## 📜 專案簡介 (Project Overview)

**東寧物語** 是一款結合 **RPG 角色扮演** 與 **商號經營** 的網頁遊戲。玩家將扮演一名渡海來台的開拓者，在承天府（今台南）開設商號，與歷史人物互動，並透過經營累積財富與聲望。

本專案採用現代化的 **TALL Stack** (Tailwind, Alpine, Laravel, Livewire) 開發，並使用 **PostgreSQL** 作為資料庫，為未來的 **AI NPC 對話** 與 **向量記憶搜尋 (RAG)** 奠定基礎。

## 🛠️ 技術棧 (Tech Stack)

* **Backend**: Laravel 11, PHP 8.2+
* **Frontend**: Livewire 3, Alpine.js, Tailwind CSS
* **Database**: **PostgreSQL 16+** (支援 pgvector)
* **Dev Tools**: Vite, Composer, npm

## 🚀 快速開始 (Getting Started)

### 1. 環境需求
確保您的開發環境已安裝：
* PHP >= 8.2
* Composer
* Node.js & npm
* **PostgreSQL >= 16**

### 2. 安裝專案
```bash
# 複製專案
git clone [https://github.com/garycsrsr/tungningstory.git](https://github.com/garycsrsr/tungningstory.git)
cd tungningstory

# 安裝 PHP 相依套件
composer install

# 安裝前端相依套件
npm install
```

### 3. 資料庫設定 (PostgreSQL)

請確保您的 `php.ini` 已啟用 Postgres 驅動：
```ini
extension=pgsql
extension=pdo_pgsql
```

複製環境設定檔並修改：
```bash
cp .env.example .env
```

修改 `.env` 中的資料庫設定：
```dotenv
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=tungning_story
DB_USERNAME=postgres  # 您的 Postgres 帳號
DB_PASSWORD=secret    # 您的 Postgres 密碼
```

### 4. 初始化
```bash
# 產生 Application Key
php artisan key:generate

# 執行資料庫遷移與填充 (建立資料表、NPC 楊英、測試帳號)
php artisan migrate:fresh --seed

# 建置前端資源
npm run build
```

### 5. 啟動開發伺服器
```bash
# 啟動 Laravel 伺服器
php artisan serve

# (可選) 啟動前端熱更新
npm run dev
```
現在訪問 [http://127.0.0.1:8000](http://127.0.0.1:8000) 即可開始遊戲！

---

## 🎮 遊戲特色功能

### 🏪 商號系統 (Shop System)
* **沉浸式開店流程**：與戶部主事 **NPC 楊英** 互動，簽署官契。
* **資源管理**：開店需消耗「通寶」與「體力」，體力會隨時間自動恢復。
* **動態回饋**：使用 Livewire Polling 實作體力值即時更新，無須刷新頁面。

### 🤖 AI 準備 (AI Readiness)
* **PostgreSQL 架構**：原生支援 JSONB 與高併發讀寫。
* **向量擴充 (未來規劃)**：預計導入 `pgvector`，將 NPC 對話紀錄向量化，實現具備長期記憶的智慧 NPC。

## 🧪 測試帳號 (Seeder)

執行 `migrate:fresh --seed` 後，系統會建立以下預設帳號：

| 角色 | Email | 密碼 | 備註 |
| :--- | :--- | :--- | :--- |
| **玩家 (User)** | `test@example.com` | `password` | 預設擁有 50000 通寶 |
| **管理員 (Admin)** | `admin@example.com` | `password` | 後台管理權限 |

## 🤝 貢獻指南
歡迎提交 Pull Request 或 Issue。請確保您的代碼符合 PSR-12 標準，並通過所有測試。

---
*© 2026 東寧物語開發團隊. All Rights Reserved.*
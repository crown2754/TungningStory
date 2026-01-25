# 東寧物語 (Tungning Story)

一款基於 Laravel Livewire 開發的明鄭時期大航海背景網頁遊戲。

## 📜 專案簡介

《東寧物語》是一個結合角色扮演與模擬經營的 Web Game。玩家將扮演明鄭時期的開拓者，在台灣進行開墾、貿易與冒險。

## 🛠️ 技術棧

- **Backend**: Laravel 12.x
- **Frontend**: Livewire 3, Volt, TailwindCSS, DaisyUI (Retro Theme)
- **Database**: MySQL / SQLite (Dev)
- **Task Scheduling**: Laravel Scheduler

## 🌟 目前已實作功能

### 1. 權限與身分系統

- **多重身分**:
    - `Player`: 一般玩家，擁有金幣、體力等遊戲數值。
    - `GM` (巡查官): 擁有進入後台權限，可查看名冊與修改數值。
    - `OM` (運營總督): 最高權限，可任命 GM，且擁有防降級保護。
- **動態導覽列**: 根據身分自動切換「府邸」(前台) 與「營運總署」(後台) 入口。

### 2. 遊戲參數與系統設定

- **府庫規章 (Game Settings)**:
    - 透過後台介面動態調整遊戲參數（如：初始金幣、初始體力、體力恢復量）。
    - 支援「動態官印 (Logo)」上傳，即時更新網站左上角標誌。
- **操作紀錄 (Audit Logs)**:
    - 記錄所有後台參數修改、人員異動。
    - 包含防護攔截紀錄（如：試圖降級 OM 時的警告）。

### 3. 體力恢復機制

- **自動排程**: 伺服器每 15 分鐘執行一次 `game:restore-stamina`。
- **批量處理**: 針對全服未滿體力的玩家進行回復，效能優化。
- **參數化**: 恢復量可透過後台動態調整（例如活動期間加倍）。

## 🚀 安裝與執行

1. **環境建置**

    ```bash
    composer install
    npm install
    npm run build
    ```

2. **資料庫設定**

    ```bash
    cp .env.example .env
    php artisan key:generate
    # 設定好 .env 資料庫連線後
    php artisan migrate --seed
    ```

    _注意：`db:seed` 會寫入預設遊戲參數與管理員帳號。_

3. **建立檔案連結 (圖片上傳用)**

    ```bash
    php artisan storage:link
    ```

4. **啟動排程 (體力恢復)**
   _開發環境 (Windows/Mac):_

    ```bash
    php artisan schedule:work
    ```

    _正式環境 (Linux):_ 設定 Crontab 每分鐘執行 `schedule:run`。

5. **啟動伺服器**
    ```bash
    php artisan serve
    ```

## 📝 預設帳號

- **運營總督 (OM)**: `service@gkgary.com` / `game@1234`
- **測試玩家**: `player@example.com` / `game@1234`

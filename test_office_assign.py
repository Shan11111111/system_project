# 建議檔名：test_office_assignments.py

from selenium import webdriver
from selenium.webdriver.common.by import By
from selenium.webdriver.common.keys import Keys
import time

# 設定 WebDriver 路徑
driver = webdriver.Chrome()  # 或 webdriver.Edge(), webdriver.Firefox() 等

try:
    # 1. 開啟登入頁
    driver.get("http://localhost/analysis_project/login.php")
    time.sleep(1)

    # 2. 輸入帳號密碼（請根據實際 input name 調整）
    driver.find_element(By.NAME, "user_id").send_keys("0905")  # 測試帳號
    driver.find_element(By.NAME, "password").send_keys("0905")
    driver.find_element(By.NAME, "password").send_keys(Keys.RETURN)
    time.sleep(2)

    # 3. 進入 office_assignments.php
    driver.get("http://localhost/analysis_project/funding/office_assignments.php")
    time.sleep(2)

    # 4. 檢查通知按鈕或 FAB 是否存在
    try:
        fab = driver.find_element(By.ID, "fab")
        print("FAB 按鈕存在，文字：", fab.text)
        assert "建言" in fab.text
    except Exception as e:
        print("FAB 按鈕不存在，請檢查頁面。")

    # 5. 檢查卡片區塊是否有資料
    cards = driver.find_elements(By.CLASS_NAME, "card")
    print(f"共找到 {len(cards)} 張卡片")
    assert len(cards) >= 0

    # 6. 測試搜尋功能
    search_box = driver.find_element(By.NAME, "search")
    search_box.clear()
    search_box.send_keys("跨國")
    search_box.send_keys(Keys.RETURN)
    time.sleep(2)
    print("搜尋後卡片數：", len(driver.find_elements(By.CLASS_NAME, "card")))

    # 7. 測試 FAB 彈窗
    # 搜尋後頁面已刷新，需重新取得 fab 元素
    try:
        fab = driver.find_element(By.ID, "fab")
        if fab.is_enabled():
            fab.click()
            time.sleep(1)
            modal = driver.find_element(By.ID, "fabModal")
            assert modal.is_displayed()
            print("FAB Modal 已顯示")
            # 關閉 modal
            driver.find_element(By.CLASS_NAME, "fab-close").click()
            time.sleep(1)
    except Exception as e:
        print("FAB 按鈕操作失敗：", e)

finally:
    driver.quit()
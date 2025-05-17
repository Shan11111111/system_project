from selenium import webdriver
from selenium.webdriver.common.by import By
from selenium.webdriver.common.keys import Keys
import time

driver = webdriver.Chrome()

try:
    # 1. 開啟登入頁
    driver.get("http://localhost/analysis_project/login.php")
    time.sleep(1)

    # 2. 輸入帳號密碼（請根據實際 input name 調整）
    driver.find_element(By.NAME, "user_id").send_keys("333333")  # 測試帳號
    driver.find_element(By.NAME, "password").send_keys("333333")
    driver.find_element(By.NAME, "password").send_keys(Keys.RETURN)
    time.sleep(2)

    # 3. 進入會員中心
    driver.get("http://localhost/analysis_project/member_center.php")
    time.sleep(2)

    # 4. 驗證側邊欄存在
    sidebar = driver.find_element(By.CLASS_NAME, "member-sidebar")
    print("側邊欄存在")

    # 5. 依序點擊側邊欄各功能，檢查 iframe src 是否正確
    sidebar_links = sidebar.find_elements(By.TAG_NAME, "a")
    iframe = driver.find_element(By.NAME, "memberContent")

    for link in sidebar_links:
        link_text = link.text
        link.click()
        time.sleep(1)
        # 取得 iframe src
        src = iframe.get_attribute("src")
        print(f"點擊「{link_text}」, iframe src: {src}")
        assert src != "", "iframe src 應有值"
        # 可根據功能名稱進一步驗證 src 是否正確

    print("所有側邊欄功能點擊測試完成")

finally:
    driver.quit()
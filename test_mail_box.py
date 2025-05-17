from selenium import webdriver
from selenium.webdriver.common.by import By
from selenium.webdriver.common.keys import Keys
import time

driver = webdriver.Chrome()

try:
    # 1. 如需登入，請打開登入頁並登入（可略過此段，若意見箱不需登入）
    # driver.get("http://localhost/analysis_project/login.php")
    # time.sleep(1)
    # driver.find_element(By.NAME, "user_id").send_keys("0905")
    # driver.find_element(By.NAME, "password").send_keys("0905")
    # driver.find_element(By.NAME, "password").send_keys(Keys.RETURN)
    # time.sleep(2)

    # 2. 進入意見箱頁面
    driver.get("http://localhost/analysis_project/suggestion_box.php")
    time.sleep(2)

    # 3. 填寫表單
    driver.find_element(By.NAME, "name").send_keys("自動化測試")
    driver.find_element(By.NAME, "email").send_keys("09shan2005@gmail.com")
    driver.find_element(By.NAME, "message").send_keys("這是自動化測試訊息")
    driver.find_element(By.CSS_SELECTOR, "button[type='submit']").click()
    time.sleep(2)

    # 4. 驗證是否跳轉或顯示成功訊息
    if "successmail.php" in driver.current_url:
        print("意見箱自動化測試成功，已跳轉到 successmail.php")
    else:
        # 嘗試找尋成功訊息
        success = False
        try:
            # 假設有顯示「送出成功」等訊息
            if "Form submitted successfully!" in driver.page_source or "感謝您的意見" in driver.page_source:
                print("意見箱自動化測試成功，頁面顯示送出成功訊息")
                success = True
        except Exception as e:
            pass
        assert success, "未跳轉且未找到送出成功訊息"

finally:
    driver.quit()
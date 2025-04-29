from selenium import webdriver
from selenium.webdriver.common.by import By
from selenium.webdriver.common.keys import Keys
from selenium.webdriver.support.ui import WebDriverWait
from selenium.webdriver.support import expected_conditions as EC
import time

# 配置 Chrome Driver
driver = webdriver.Chrome()

# 開啟首頁
driver.get('http://localhost/analysis_project/homepage.php')

# 等待 "募資進度" 元素加載
wait = WebDriverWait(driver, 10)
seek_advice = wait.until(EC.presence_of_element_located((By.CLASS_NAME, 'seek_advice')))

# 滾動頁面直到 "募資進度" 出現
driver.execute_script("arguments[0].scrollIntoView(true);", seek_advice)

# 滑動時間 (可選)
time.sleep(2)  # 可根據需求調整等待時間

# 再次確保「募資進度」出現在視窗中
time.sleep(2)  # 等待滾動完成

# 檢查這個元素是否已經在視窗中
if seek_advice.is_displayed():
    print("建言進度選項已顯示")
    try:
        # 嘗試點擊元素
        seek_advice.click()
    except Exception as e:
        print("無法點擊元素，嘗試使用 JavaScript 點擊")
        # 如果無法點擊，使用 JavaScript 觸發點擊
        driver.execute_script("arguments[0].click();", seek_advice)
else:
    print("建言進度選項仍然不可見")

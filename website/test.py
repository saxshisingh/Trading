import sys
import json
from breeze_connect import BreezeConnect
import login as l

breeze = BreezeConnect(api_key=l.api_key)
breeze.generate_session(api_secret=l.api_secret, session_token=l.session_key)
print(breeze.get_quotes(stock_code="ICIBAN",
                    exchange_code="NFO",
                    expiry_date="2022-08-25T06:00:00.000Z",
                    product_type="futures",
                    right="others",
                    strike_price="0"))
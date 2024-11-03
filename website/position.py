
import sys
import json
from breeze_connect import BreezeConnect 
import login as l 

def main():
    breeze = BreezeConnect(api_key=l.api_key)
    breeze.generate_session(api_secret=l.api_secret, session_token=l.session_key)

    portfolio_positions = breeze.get_portfolio_positions()

    print(json.dumps(portfolio_positions)) 

if __name__ == "__main__":
    main()
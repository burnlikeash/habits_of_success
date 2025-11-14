FROM python:3.11-slim

WORKDIR /code

COPY requirements.txt .
RUN pip install --default-timeout=100 --no-cache-dir -r requirements.txt

COPY . /code/app

EXPOSE 8000

CMD ["uvicorn", "app.server:app", "--host", "0.0.0.0", "--port", "8000"]

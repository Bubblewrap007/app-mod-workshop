#!/bin/bash
# Deploy script for my-php-app to Cloud Run
# Usage: ./gcloud-run-deploy.sh [dev|prod]

ENV=${1:-dev}
PROJECT_ID="awesome-project-489421"
PROJECT_NUMBER="377411193239"
REGION="us-central1"
SERVICE_NAME="my-php-app"
IMAGE="us-central1-docker.pkg.dev/${PROJECT_ID}/cloud-run-source-deploy/${SERVICE_NAME}:latest"

gcloud run deploy ${SERVICE_NAME} \
  --image=${IMAGE} \
  --region=${REGION} \
  --project=${PROJECT_ID} \
  --allow-unauthenticated \
  --service-account="${PROJECT_NUMBER}-compute@developer.gserviceaccount.com" \
  --set-env-vars="DB_HOST=35.192.15.69,DB_NAME=image_catalog,DB_USER=appmod-phpapp-user" \
  --set-secrets="DB_PASS=php-amarcord-db-pass:latest" \
  --execution-environment=gen2 \
  --add-volume=name=php_uploads,type=cloud-storage,bucket=awesome-project-489421-images \
  --add-volume-mount=volume=php_uploads,mount-path=/var/www/html/uploads/

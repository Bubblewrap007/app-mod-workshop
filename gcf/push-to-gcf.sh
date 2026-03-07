#!/bin/bash

set -euo pipefail

PROJECT_ID="awesome-project-489421"
GCP_REGION="us-central1"
BUCKET="awesome-project-489421-images"
DB_HOST="35.192.15.69"
DB_NAME="image_catalog"
DB_USER="appmod-phpapp-user"
DB_PASS="Cloudedminday3@"

echo "Deploying Cloud Function to project $PROJECT_ID, bucket $BUCKET..."

gcloud functions deploy php_amarcord_generate_caption \
    --project "$PROJECT_ID" \
    --runtime python310 \
    --region "$GCP_REGION" \
    --trigger-event-filters="type=google.cloud.storage.object.v1.finalized" \
    --trigger-event-filters="bucket=$BUCKET" \
    --set-env-vars "PROJECT_ID=$PROJECT_ID,DB_HOST=$DB_HOST,DB_NAME=$DB_NAME,DB_USER=$DB_USER,DB_PASS=$DB_PASS" \
    --source . \
    --entry-point generate_caption \
    --memory 512MB \
    --timeout 120s \
    --gen2

echo "Done!"

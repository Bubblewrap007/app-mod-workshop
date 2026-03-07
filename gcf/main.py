#!/usr/bin/env python

"""
Cloud Function: generate_caption
Triggered when a new image lands in GCS.
Calls Gemini to describe the image, then updates the DB with the caption.
"""

import os
import functions_framework
import pymysql
import pymysql.cursors
import vertexai
from vertexai.generative_models import GenerativeModel, Part

PROJECT_ID    = os.environ.get("PROJECT_ID", "awesome-project-489421")
GEMINI_MODEL  = "gemini-1.5-flash-002"
DEFAULT_PROMPT = "Generate a short, descriptive caption for this image."

SUPPORTED_MIME_TYPES = {"image/png", "image/jpeg", "image/gif", "image/webp"}


def gemini_describe_image_from_gcs(gcs_url, mime_type, image_prompt=DEFAULT_PROMPT):
    """Call Gemini to describe an image stored in GCS."""
    vertexai.init(project=PROJECT_ID, location="us-central1")
    model = GenerativeModel(GEMINI_MODEL)
    image_part = Part.from_uri(gcs_url, mime_type=mime_type)
    response = model.generate_content([image_part, image_prompt])
    return response.text.strip()


def update_db_with_description(image_filename, caption, db_user, db_pass, db_host, db_name):
    """Update the images table with the generated caption."""
    conn = pymysql.connect(
        host=db_host,
        user=db_user,
        password=db_pass,
        database=db_name,
        cursorclass=pymysql.cursors.DictCursor,
    )
    with conn:
        with conn.cursor() as cursor:
            sql = "UPDATE images SET description = %s WHERE filename = %s"
            cursor.execute(sql, (caption, image_filename))
        conn.commit()


@functions_framework.cloud_event
def generate_caption(cloud_event):
    """
    Cloud Function triggered by a GCS finalize event.
    Describes the image with Gemini and stores the caption in the DB.
    """
    data = cloud_event.data
    bucket_name  = data.get("bucket")
    object_name  = data.get("name")
    content_type = data.get("contentType", "")

    print(f"Received event for gs://{bucket_name}/{object_name} ({content_type})")

    if content_type not in SUPPORTED_MIME_TYPES:
        print(f"Skipping unsupported type: {content_type}")
        return

    gcs_url        = f"gs://{bucket_name}/{object_name}"
    image_filename = f"uploads/{object_name}"

    caption = gemini_describe_image_from_gcs(gcs_url, content_type)
    print(f"Caption: {caption}")

    update_db_with_description(
        image_filename,
        caption,
        db_user=os.environ["DB_USER"],
        db_pass=os.environ["DB_PASS"],
        db_host=os.environ["DB_HOST"],
        db_name=os.environ["DB_NAME"],
    )
    print(f"DB updated for {image_filename}")
